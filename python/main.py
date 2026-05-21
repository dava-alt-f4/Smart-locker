import requests
from face.recognize import recognize_face
import serial

BASE_URL = "http://127.0.0.1:8000/api"

def check_rfid(rfid_uid, locker_id):
    res = requests.post(f"{BASE_URL}/rfid-check", json={
        "rfid_uid": rfid_uid,
        "locker_id": locker_id
    })
    return res.json()


def verify_face(student_id, locker_id):
    face_match = recognize_face(student_id)

    res = requests.post(f"{BASE_URL}/face-verify", json={
        "student_id": student_id,
        "locker_id": locker_id,
        "match": face_match
    })
    return res.json()


def log_access(student_id, locker_id, rfid_uid, status):
    requests.post(f"{BASE_URL}/log-access", json={
        "student_id": student_id,
        "locker_id": locker_id,
        "rfid_uid": rfid_uid,
        "status": status
    })


def main():
    ser = serial.Serial('COM3', 9600, timeout=1)

    while True:
        if ser.in_waiting:
            data = ser.readline().decode().strip()

            if not data:
                continue

            print("DARI ARDUINO:", data)

            try:
                rfid_uid, locker_id = data.split(",")
                locker_id = int(locker_id)
            except:
                print("Format salah")
                continue
            
            
            # =========================
            # 1. RFID CHECK
            # =========================
            rfid_res = check_rfid(rfid_uid, locker_id)
            status = rfid_res["status"]

            if status != "valid":
                print("RFID gagal:", status)
                ser.write(b"FAIL\n")
                continue

            student_id = rfid_res["student_id"]

            # =========================
            # 2. FACE VERIFY
            # =========================
            face_res = verify_face(student_id, locker_id)

            if face_res["status"] != "verified":
                print("Wajah tidak cocok")
                ser.write(b"FAIL\n")

                log_access(student_id, locker_id, rfid_uid, "failed")
                continue

            # =========================
            # 3. SUCCESS
            # =========================
            print(f"Loker {locker_id} terbuka")
            ser.write(b"OK\n")

            log_access(student_id, locker_id, rfid_uid, "success")


if __name__ == "__main__":
    main()