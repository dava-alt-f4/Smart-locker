import requests

BASE_URL = "http://127.0.0.1:8000/api"

def check_rfid(rfid_uid):
    res = requests.post(f"{BASE_URL}/rfid-check", json={
        "rfid_uid": rfid_uid
    })
    return res.json()


def verify_face(student_id):
    # sementara simulasi (nanti diganti AI)
    face_match = input("Face match? (y/n): ")

    res = requests.post(f"{BASE_URL}/face-verify", json={
        "student_id": student_id,
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
    # simulasi scan RFID
    while True:
        rfid_uid = input("Scan RFID (ketik UID): ")
        if rfid_uid:
            break

    # 1. cek RFID
    rfid_res = check_rfid(rfid_uid)
    print("RFID:", rfid_res)

    if rfid_res["status"] != "valid":
        print("RFID tidak valid ❌")
        return

    student_id = rfid_res["student_id"]

    # 2. face recognition
    face_res = verify_face(student_id)
    print("Face:", face_res)

    if face_res["status"] == "no_locker":
        print("❌ Siswa belum punya loker")
        
        log_access(student_id, None, rfid_uid, "failed")
        return
    
    if face_res["status"] != "verified":
        print("Wajah tidak cocok ❌")

        log_access(student_id, None, rfid_uid, "failed")
        return

    locker_id = face_res["locker_id"]

    # 3. buka loker (simulasi)
    print(f"Loker {locker_id} terbuka 🔓")

    # 4. simpan log
    log_access(student_id, locker_id, rfid_uid, "success")


if __name__ == "__main__":
    main()