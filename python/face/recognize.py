import face_recognition
import cv2
import os
import time

start_time = time.time()
timeout = 5  # detik

def load_student_encodings(student_id):
    path = f"dataset/{student_id}"
    encodings = []

    if not os.path.exists(path):
        return []

    for file in os.listdir(path):
        img_path = os.path.join(path, file)
        image = face_recognition.load_image_file(img_path)
        face_enc = face_recognition.face_encodings(image)

        if face_enc:
            encodings.append(face_enc[0])

    return encodings


def recognize_face(student_id):
    start_time = time.time()
    timeout = 10

    known_encodings = load_student_encodings(student_id)

    if not known_encodings:
        print("Dataset kosong ❌")
        return False

    cap = cv2.VideoCapture(0, cv2.CAP_DSHOW)

    if not cap.isOpened():
        print("❌ Kamera tidak bisa dibuka")
        return False

    match_count = 0
    while True:
        if time.time() - start_time > timeout:
            print("Wajah tidak cocok ❌")
            cap.release()
            cv2.destroyAllWindows()
            return False

        ret, frame = cap.read()

        if not ret:
            continue

        rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)

        face_locations = face_recognition.face_locations(rgb)

        if len(face_locations) == 0:
            cv2.imshow("Face Recognition", frame)
            continue

        face_encodings = face_recognition.face_encodings(rgb, face_locations)


        for face_encoding in face_encodings:
            matches = face_recognition.compare_faces(known_encodings, face_encoding)

            if True in matches:
                match_count += 1

        if match_count >= 3:
            print("Wajah cocok ✅")
            cv2.imshow("Face Recognition", frame)
            cv2.waitKey(1000)

            cap.release()
            cv2.destroyAllWindows()
            return True

        cv2.imshow("Face Recognition", frame)

        if cv2.waitKey(1) & 0xFF == ord('q'):
            break
    
    cap.release()
    cv2.destroyAllWindows()
    print("Wajah tidak cocok ❌")
    return False