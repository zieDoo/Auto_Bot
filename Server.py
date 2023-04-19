import sys
import socket
import requests
# from collections import Mapping
# from collections.abc import Mapping
from credentials import USER, PASSWORD, SERVER, LOGIN_LINK, NEXT_LINK, LOGOUT_LINK, HUNT_LINK, LINK
from bs4 import BeautifulSoup as bs
from selenium import webdriver
from urllib.parse import urlparse
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.support.ui import Select
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC



class WebScrapper:

    def __init__(self):        
        options = webdriver.ChromeOptions()
        options.add_argument('--headless')
        self.driver = webdriver.Chrome(options = options)

    def login(self, user, link, password):
        self.user = user     
        self.password = password
        self.driver.get(link)

        login_area = Select(self.driver.find_element_by_xpath("//select[@name='server']"))
        login_area.select_by_visible_text("Oblasť 20")

        self.driver.find_element_by_id('loginName').send_keys(user)
        self.driver.find_element_by_id('loginPw').send_keys(password)
        self.driver.find_element_by_id('loginButton').click()
        # return self.driver.page_source

    def get_page_content(self, current_page):
        self.driver.get(current_page)
        page_source = self.driver.page_source
        soup = bs(page_source, 'html5lib')
        # print('Polievka', soup)
        return soup

    def logout(self):
        self.driver.close()
        self.driver.quit()


def get_info_from_infobar_table(html_content) -> dict:

    full_table = html_content.find('div', {'class': 'gold'})
    table_content_as_str = html_content.find('div', {'class': 'gold'}).text
    formated_string = ' '.join(table_content_as_str.split()).replace(' / ', '/')
    table_values_list = list(formated_string.split(' '))

    # tags_in_list = [tags for tags in full_table.find_all('img') if tags.has_attr('alt')]

    # --- LIST COMPREHENSION FOR ALT VALUES ---
    # -----------------------------------------
    alt_names_from_table = [tags['alt'] for tags in full_table.find_all('img', alt = True)]
    table_ids_list = alt_names_from_table

    # print(f'ALT NAMS : {alt_names_from_table} and it is type: {type(alt_names_from_table)}')
    # print(alt_names_from_table)

    # --- ALT VALUES VIA USUAL FOR LOOP ---
    # -------------------------------------
    # alt_names_from_table = []
    # for tags in full_table.find_all('img', alt = True):
    #     alt_names_from_table.append(tags['alt'])
    # print(alt_names_from_table)


    status_bar_dictionary = dict(zip(table_ids_list, table_values_list))
    # print('zipped: ', status_bar_dictionary

    return status_bar_dictionary




create_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

# importaaant - umozni znova otvorenie adresy.
create_socket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)

create_socket.bind(("localhost", 9988))
create_socket.listen(1)

wrapper = WebScrapper()

first_connection = True

if first_connection:
    #wrapper.login(LOGIN_LINK, USER, PASSWORD, SERVER)
    wrapper.login(USER, LINK, PASSWORD)
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

        # print('show')
        content = wrapper.get_page_content(NEXT_LINK)

        # print(content.page_source)
        status_bar = get_info_from_infobar_table(content)

        # print('Obsah z infobar tabulky ?', tabulka)
        status_bar_stringed = str(status_bar)
        status_bar_stringed_encoded = status_bar_stringed.encode("utf-8")
        
        # send back to local
        conn.sendall(status_bar_stringed_encoded)        

    elif a == "logout":
        print('logout')        
        wrapper.logout()
        conn.close()
        break

    else:
        pass
