#loading imports ; namespaces and classes
import setproctitle
import sys
import cv2
import datetime
import time
import os
from picamera import PiCamera
from picamera.array import PiRGBArray
import imutils
import warnings
import smtplib
from email.MIMEMultipart import MIMEMultipart
from email.MIMEText import MIMEText
from email.MIMEBase import MIMEBase
from email import encoders
from email.mime.image import MIMEImage
from dropbox.client import DropboxOAuth2FlowNoRedirect
from dropbox.client import DropboxClient

setproctitle.setproctitle("project")
    
#variables:
framerate = int(sys.argv[1]) #input("Enter Frame Rate:   \b")
threshVal = int(sys.argv[2]) #input("Enter Thresh Value to compare:  ") 
avgFrame = None
area = int(sys.argv[3]) #input("Enter Minimum area of object that is need not to be tracked: ")
upldInt =int(sys.argv[4])#input("Enter minimum interval time in seconds between consecutive frames to upload  ")
minMotion =  int(sys.argv[5])#input("Minimum number of frames to wait until upload:  ")
filePath =  "/home/pi/Test/"
camera = PiCamera() #PiCamera object instantiated as camera
time.sleep(1)
camera.resolution = (960,720)
camera.framerate = framerate
text = None
upload = True
frameCounter=0
tempUpload = None
motionCounter = 0
lastUploaded= datetime.datetime.now()
rawCap = PiRGBArray(camera, size=(960,720)) #RGB array instantiated as rawCap

### Mail variables 
mailInterval = int(sys.argv[6])
toaddr = str(sys.argv[7])

fromaddr = "enter your email"
passWord = "enter your password" 
mail = MIMEMultipart()
mail['From'] = fromaddr
mail['To'] = toaddr
mail['Subject'] = "Unauthorized Movement"
body = "Movement Detected"
lastMailed = datetime.datetime.now() - datetime.timedelta(seconds = (mailInterval-60)) # initializaiton time 100 seconds


### Cloud Storage Variables
warnings.filterwarnings("ignore")
###dont edit the access token
accessToken = str(sys.argv[8])
client = DropboxClient(accessToken)

'''
#accessToken="fZ3n3urROWAAAAAAAAABoq4_o7ZWzCdvXZUkH8qKX0KJF0ZXzDFvkQIBeG9Oj1Nn"
client = None
if upload == True:
    if accessToken == "":
        flow = DropboxOAuth2FlowNoRedirect("g71cdtt783acg1a", "8tmdm9xawxa7mwq")
        print "[INFO] Authorize this application: {}".format(flow.start())
        authCode = raw_input("Enter Auth Code Here: ").strip()
        (accessToken, userID) = flow.finish(authCode)
    
    
    client = DropboxClient(accessToken)
    #print "[SUCCESS] dropbox account linked"
else:
    print "Failed to link"
'''


### Send Mail
def sendMail():
    #print data
    #dat='%s.jpg'%data
    #print dat
    #attachment = open(dat, 'rb')
    #image=MIMEImage(attachment.read())
    #attachment.close()
    #mail.attach(image)
    
    prsntTime= datetime.datetime.now()
    global lastMailed,mailInterval
    diff = (prsntTime-lastMailed).seconds

    #print diff,"   ",mailInterval
    
    if (prsntTime - lastMailed).seconds >= mailInterval:
        mail.attach(MIMEText(body, 'plain'))
        server = smtplib.SMTP('smtp.gmail.com', 587)
        server.starttls()
        server.login(fromaddr, passWord)
        text = mail.as_string()
        server.sendmail(fromaddr, toaddr, text)
        server.quit()
        lastMailed = datetime.datetime.now()


#capturing frame for processing: 
for curFrame in camera.capture_continuous(rawCap,format="bgr", use_video_port= True):
    
    image = curFrame.array
    uploadCopy = curFrame.array 
    text = "Every Thing is in place"
    timestamp = datetime.datetime.now()
    image = imutils.resize(image, width=400)
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    grayBlurred = cv2.GaussianBlur(gray, (21,21), 0)
    
    if avgFrame is None:
        #print "Initializing Background Model: \n"
        avgFrame = grayBlurred.copy().astype("float")
        rawCap.truncate(0)
        continue

    cv2.accumulateWeighted(grayBlurred,avgFrame,0.5)
    frameDelta = cv2.absdiff(grayBlurred,cv2.convertScaleAbs(avgFrame))
    thresheed = cv2.threshold(frameDelta, threshVal, 255, cv2.THRESH_BINARY)[1]                                     
    thresheedDilated = cv2.dilate(thresheed, None, iterations=2) 

    (cnts, _) = cv2.findContours(thresheedDilated.copy(),  cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    for c in cnts:
        if cv2.contourArea(c) < area:
            continue
        (x, y, w, h) = cv2.boundingRect(c)
        cv2.rectangle(gray, (x,y), (x+w, y+h), (0,255,0), 2)
        
        text = "Moved"

    ts = timestamp.strftime("%A %d %B %Y %I:%M:%S%p")
    cv2.putText(image, "Room Status: {}".format(text), (10, 20),
        cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 0, 255), 2)
    cv2.putText(image, ts, (10, image.shape[0] - 10), cv2.FONT_HERSHEY_SIMPLEX,
        0.35, (0, 0, 255), 1)
    
    if text == "Moved":
        sendMail()
        if(timestamp - lastUploaded).seconds >= upldInt:
            motionCounter = motionCounter + 1
            if motionCounter >= minMotion:
                if upload == True:
                    filename = datetime.datetime.now().strftime("%b %d %Y %H:%M:%S") + ".jpg"
                    tempUpload = filename #only file name
                    filename = filePath + filename  #file name with path
                    cv2.imwrite(filename,uploadCopy)#write to temp storage
                    temp = open(filename,"rb")
                    response = client.put_file(tempUpload, temp)
                    if response:
                        os.remove(filename)
                    lastUploaded = timestamp
                    rawCap.truncate(0)
                motionCounter = 0 
    else:
        motionCounter = 0
    
    #cv2.imshow("Original", image)
    #cv2.imshow("GreyScale", gray)
    #cv2.imshow("Grey Blurred", grayBlurred)
    #cv2.imshow("Average Frame Initial", avgFrame)
    #cv2.imshow("Threshed", thresheed )
    #cv2.imshow("Threshed and dialated", thresheedDilated)
    rawCap.truncate(0)
    
    #frame capture ends
    key = cv2.waitKey(1) & 0xFF
    if key == ord("x"):
        break


#cv2.destroyAllWindows()
