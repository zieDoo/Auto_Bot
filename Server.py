import sys
import socket
import requests
import json
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

    # print('TENTO: ', table_ids_list)

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



def get_info_from_character_table(html_content) -> dict:

    character_table = html_content.find('div', {'id': 'character_tab'})
    # print('CAR TABLE: ', character_table)

    character_full_table_dictionary = {}

    for row in character_table.find_all('tr'):
        columns = row.find_all('td')
        # print(columns)
        # key, value = [col.text.strip() if col == 'Liečenie zdravia' else col == 'Akčné body Regenerácia' for col in columns]
        key, value = [cols.text.strip().rstrip(':') if i == 0 else cols.text.strip() for i, cols in enumerate(columns)]

        character_full_table_dictionary[key] = value

    # Odstranime meniace sa hodnoty zo slovnika - ostavajuci cas liecenia a regeneracia zivotov.
    character_table_dictionary = {key: value for i, (key, value) in enumerate(character_full_table_dictionary.items()) if i <= 6}

    # print(character_full_table_dictionary)
    return character_table_dictionary
    


def get_info_from_skills_table(html_content) -> dict:

    skills_table = html_content.find('div', {'id': 'skills_tab'})
    # print('SKILLS_TAB: ', skills_table)

    # ids = []

    # --- Z PRVEJ TABULKY, PRE VSETKY TR, VYBER LEN PRVY TD

    # for ts in skills_table.select('tbody:nth-of-type(1) > tr > td:nth-of-type(1)'):
    #     # print(ts.text)
    #     ids.append(ts.text.replace(":", ""))

    column1_cells = skills_table.select('tbody:nth-of-type(1) > tr > td:nth-of-type(1)')
    ids = [ts.text.replace(':', '') for ts in column1_cells]

    # --- OLD WAY ---
    # ---------------
    # values = []
    # haleluja = skills_table.select('tbody:nth-of-type(1) > tr > td:nth-of-type(2)') 
    #
    # for cozase in haleluja:
    # 
    #     dodo = cozase.find('div')
    #
    #     if dodo not in cozase:
    #         val2.append(cozase.text.strip("()"))
    #
    #     else:
    #         val2.append(dodo.text.replace('\n', '').replace(" ", '').strip("()"))


    # --- VIA LIST COMPREHENSION ---
    # ------------------------------
    column2_cells = skills_table.select('tbody:nth-of-type(1) > tr > td:nth-of-type(2)')
    values = [ cell.find('div').text.replace('\n', '').replace(' ', '').strip('()') if cell.find('div') is not None else cell.text.strip('()') for cell in column2_cells]

    skill_table_dictionary = dict(zip(ids, values))

    return skill_table_dictionary
    


def merge_results(html_content) -> dict:
    
    all_tables = {}
    all_tables.update(get_info_from_infobar_table(html_content))
    all_tables.update(get_info_from_character_table(html_content))
    all_tables.update(get_info_from_skills_table(html_content))

    return all_tables



def get_action_points(html_content) -> list:

    all_values = get_info_from_infobar_table(html_content)
    all_action_points = list(all_values.items())[3]
    all_values_from_action_points = all_action_points[1]
    splitted_action_points = all_values_from_action_points.split('/')
    
    actual_points = splitted_action_points[0]
    all_points = splitted_action_points[1]

    action_point_list = [actual_points, all_points]

    # print(f'Info about Action Points: {int(actual_points)} a {int(all_points)}')
    # print(action_point_list)
    # print(type(action_point_list))
    return action_point_list



def get_character_links(html_content) -> list:

    # all_links = html_content.find_element_by_css_selector('a[href="#tabs-2"]')
    # one_links = html_content.select('a[href="#tabs-2"]')
    # print('found all links: ', one_links)

    all_training_links = html_content.select('a[href*="training"] img')

    # for img in all_training_links:
    #     links = img.find_parent('a', href = True)
    #     print('Character links: ', links['href'])

    # Vylistovanie linkov pre character

    traning_links = [ img.find_parent('a', href = True)['href'] for img in all_training_links]
    # print(traning_links)
    # print(*traning_links, sep = '\n')

    return traning_links




# Vytvor socket pre obojstrannu komunikaciu s client scriptom
create_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

# IMPORTANT - umozni znova otvorenie adresy.
create_socket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)

# Adresa a port
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
    # print("Connection accepted")

    data = conn.recv(1024)
    # print("Data received")

    a = data.decode('utf-8')
    # print("Data decoded")

    if a == "start":
        print("start")

    elif a == "stop":
        print("stop")

    elif a == "update":
        content = wrapper.get_page_content(NEXT_LINK)

        # get_action_points(content)
        get_character_links(content)
        
        # print('update')

        # conn.sendall("{'Zlato': '88.933', 'Pekelné kamene': '0', 'Úlomky': '22', 'Akčné body': '125/125', 'Energia': '23.600/23.600', 'Úroveň': '11', 'Bojová hodnota': '111'}".encode('utf-8'))

    elif a == "show":

        # print('show')
        content = wrapper.get_page_content(NEXT_LINK)

        all_info = merge_results(content)
        # print(all_info)

        status_bar_stringed = str(all_info)
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
