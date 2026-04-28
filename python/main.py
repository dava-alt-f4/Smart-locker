import requests
from face.recognize import recognize_face

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
    data = input("Dari Arduino (UID,LOCKER): ")
    rfid_uid, locker_id = data.split(",")
    locker_id = int(locker_id)

    # 1. RFID check
    rfid_res = check_rfid(rfid_uid, locker_id)
    status = rfid_res["status"]

    if status == "invalid":
        print("❌ RFID tidak terdaftar")
        print("KIRIM KE ARDUINO: DENIED")
        return

    elif status == "no_locker":
        print("❌ Siswa belum punya loker")
        print("KIRIM KE ARDUINO: DENIED")
        return

    elif status == "wrong_locker":
        print("❌ RFID bukan milik loker ini")
        print("KIRIM KE ARDUINO: DENIED")
        return

    student_id = rfid_res["student_id"]

    # 2. Face verify
    face_res = verify_face(student_id, locker_id)

    if face_res["status"] != "verified":
        print("❌ Wajah tidak cocok")
        print("KIRIM KE ARDUINO: DENIED")

        log_access(student_id, locker_id, rfid_uid, "failed")
        return

    # 3. SUCCESS
    print(f"✅ Loker {locker_id} terbuka")
    print("KIRIM KE ARDUINO: OPEN")

    log_access(student_id, locker_id, rfid_uid, "success")


if __name__ == "__main__":
    main()