import sys
import base64
import json
import socket
import socketserver

# -------------------------- PRIJIMANIE A ODOSIELANIE SPRAV ---------------------------------

input_data = sys.argv[1]
input_data_decoded = input_data.encode('utf-8')
new_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

new_socket.connect(("localhost", 9988))
new_socket.sendall(input_data_decoded)

received_data = b""
new_socket.settimeout(2)

try:
    while True:
        data_chunk = new_socket.recv(1024)
        if not data_chunk:
            break
        received_data += data_chunk
except socket.timeout as sockErr:
    pass

# print(received_data)
# ----------------- TENTO PREC AK DOKONCIS ----------------------
# print(received_data.decode('utf-8'))
# ---------------------------------------------------------------

encoded_data = base64.b64encode(received_data).decode('utf-8')
print(encoded_data)

# ----------------- QUICK TESTING ----------------------

# after_decode = received_data.decode('utf-8')
# print(after_decode)
# print(type(after_decode))
# print(100*'-')
# print(received_data.decode('utf-8'))
# print(received_data)
# print(type(received_data))

new_socket.close()

# print('Check if message passed')