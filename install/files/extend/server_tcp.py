import socket
import thread
import time
import subprocess

HOST = '127.0.0.1'     # Endereco IP do Servidor
PORT = 9999            # Porta que o Servidor esta
 
def conectado(con, cliente):
   print 'Conectado por', cliente

   while True:
    msg = con.recv(1024)
    if not msg: break
    print cliente, msg
    subprocess.call(msg,shell=True)
    con.sendto('OK',cliente)    
    print 'Finalizando conexao do cliente', cliente
    con.close()
    thread.exit()
 
tcp = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
orig = (HOST, PORT)
tcp.bind(orig)
tcp.listen(1)
 
while True:
   con, cliente = tcp.accept()
   thread.start_new_thread(conectado, tuple([con, cliente]))
 
tcp.close()
