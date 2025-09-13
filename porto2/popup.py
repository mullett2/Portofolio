import mysql.connector
import tkinter as tk
import time
from tkinter import ttk, messagebox

time.sleep(35)

conn = mysql.connector.connect(
    host="localhost",
    user="root", 
    password="",
    database="webbapp_karyawan"
)
cursor = conn.cursor()

cursor.execute("""
               SELECT nama_karyawan, kontrak_selesai 
               FROM karyawan
               WHERE DATEDIFF(kontrak_selesai, CURDATE()) <= 30
               """)
results = cursor.fetchall()
conn.close()

if results:
    root = tk.Tk()
    root.title("Kontrak Karyawan")
    root.geometry("400x300")
    
    label = tk.Label(root, text="Kontrak karyawan yang akan habis:", font=("Arial", 12, "bold"))
    label.pack(pady=10)
    
    tree = ttk.Treeview(root, columns=("Nama", "Tanggal"), show="headings")
    tree.heading("Nama", text="Nama")
    tree.heading("Tanggal", text="Tanggal Habis")
    tree.pack(expand=True, fill="both")
    
    for nama, tgl in results:
        tree.insert("", "end", values=(nama, str(tgl)))
        
    root.mainloop()
else:
    root = tk.Tk()
    root.withdraw()
    messagebox.showinfo("Reminder", "Tidak ada kontrak yang akan habis")
