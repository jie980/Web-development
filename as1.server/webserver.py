import sys
import socket
#function that uses for analysis request header
def receive(client_connection):
    request_data = b''
    while True:
      new_data = client_connection.recv(4098)
      #if nothing recvive
      if (len(new_data) == 0):
        # client disconnected
        return None, None
      request_data += new_data
      # once we finish reading header. stop loop
      if b'\r\n\r\n' in request_data:
        break

    #split the request header and body
    parts = request_data.split(b'\r\n\r\n', 1)
    header = parts[0]
    body = parts[1]
    #get the content length of body
    if b'Content-Length' in header:
      headers = header.split(b'\r\n')
      for h in headers:
        if h.startswith(b'Content-Length'):
          blen = int(h.split(b' ')[1]);
          break

    else:
        blen = 0
    #read all the request body and save it
    while len(body) < blen:
      body += client_connection.recv(4098)
    print('REQUEST HEADER--------------------------')
    print(header.decode('utf-8', 'replace'), flush=True)
    print('REQUEST BODY----------------------------')
    print(body.decode('utf-8', 'replace'), flush=True)
    #return the request header and body
    return header, body


HOST = sys.argv[1]
PORT = int(sys.argv[2])
PATH = sys.argv[3]
print(HOST,PORT,PATH)
print(type(HOST),type(PORT),type(PATH))
listen_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
listen_socket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
listen_socket.bind((HOST, PORT))
listen_socket.listen(1)
print(f'Serving HTTP on port {PORT} ...')
while True:
    print('\nWaiting to connect')
    #get the client socker and client address:port
    client_connection, client_address = listen_socket.accept()
    print('connected\n')
    #get the request header and body from client
    header, body = receive(client_connection)
    #get the route that user enter
    detail_path = header.split(b' ')[1]

    #print(PATH)
    #print(detail_path.decode('UTF-8'))
    
    #get the request content type
    input_type = ''
    if b'jpg' in detail_path:
        input_type = 'image/jpg'
    elif b'png' in detail_path:
        input_type = 'image/png'
    elif b'html' in detail_path:
        input_type = 'text/html'

    #build a http response header
    http_response = 'HTTP/1.1 200 OK\r\nContent-Type:'+input_type+'; charset=UTF-8\r\n\r\n'
    #print(http_response)
    http_response = http_response.encode(encoding='UTF-8')
    str_detail_path = detail_path.decode('UTF-8')
    #the full path of the file on server
    full_path = PATH+str_detail_path
    print('FULL PATH:--------------------------')
    print(full_path)
    try:
        #reject FireFox client
        if b'Firefox' in header:
            http_response =  """\
HTTP/1.1 200 OK
Content-Type: text/html; charset=UTF-8

<html>
<body>
<i>Please swicth to other browser</i>
</body>
</html>
"""
            http_response = http_response.replace('\n','\r\n')
            client_connection.sendall(http_response.encode(encoding='UTF-8'))
          
        else:
            with open(full_path,'rb') as file:
                http_response += file.read()
            client_connection.sendall(http_response)
    except:
        http_response = """\
HTTP/1.1 404 Page Not Found
Content-Type: text/html; charset=UTF-8

<html>
<body>
<i>404 not found</i>
</body>
</html>
"""
        http_response = http_response.replace('\n','\r\n')
        client_connection.sendall(http_response.encode(encoding='UTF-8'))
    client_connection.close()
