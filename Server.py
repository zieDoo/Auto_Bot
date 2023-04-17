import sys
import socket
# import requests
# from collections import Mapping
# from collections.abc import Mapping
from bs4 import BeautifulSoup as bs
from selenium import webdriver
from urllib.parse import urlparse
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.support.ui import Select
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

USER = 'Lichandro'
PASSWORD = '16pwd12*kerbATT01'
SERVER = 's20-sk.bitefight.gameforge.com'
LOGIN_LINK  = 'https://s20-sk.bitefight.gameforge.com/user/login'
NEXT_LINK = 'https://s20-sk.bitefight.gameforge.com/profile/index'
LOGOUT_LINK = 'https://s20-sk.bitefight.gameforge.com/user/logout'
HUNT_LINK = 'https://s20-sk.bitefight.gameforge.com/robbery/index'
LINK = "https://sk.bitefight.gameforge.com/game"


class WebScrapper:

    def __init__(self, link, user, password):
        self.link = link
        self.user = user
        self.password = password
        options = webdriver.ChromeOptions()
        options.add_argument('--headless')
        self.driver = webdriver.Chrome(options = options)

    def login(self):
        pass

    def logout(self):
        self.driver.close()
        self.driver.quit()


create_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

# importaaant - umozni znova otvorenie adresy.
create_socket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)

create_socket.bind(("localhost", 9988))
create_socket.listen(1)

wrapper = WebScrapper(LINK, USER, PASSWORD)

first_connection = True

if first_connection:
    #wrapper.login(LOGIN_LINK, USER, PASSWORD, SERVER)

    wrapper.login()
    print("First connection - Server started: ")
    first_connection = False

while True:

    conn, addr = create_socket.accept()
    print("Connection accepted")

    data = conn.recv(1024)
    print("Data received")

    a = data.decode('utf-8')
    print("Data decoded")

    if a == "start":
        print("start")

    elif a == "stop":
        print("stop")

    elif a == "update":
        print('update')

    elif a == "show":
        print('show')

    elif a == "logout":
        print('logout')        
        wrapper.logout()
        conn.close()
        break

    else:
        pass
