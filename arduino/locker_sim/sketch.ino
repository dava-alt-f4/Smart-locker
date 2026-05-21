#include <SPI.h>
#include <MFRC522.h>

#define LOCKER_ID 1

#define SS_PIN 10
#define RST_PIN 9

#define LED_HIJAU 6
#define LED_MERAH 7

MFRC522 rfid(SS_PIN, RST_PIN);

String uidString = "";
bool waitingResponse = false;

// =========================
// FUNCTION OUTPUT
// =========================

void aksesBerhasil() {
  Serial.println("AKSES BERHASIL");

  digitalWrite(LED_HIJAU, HIGH);
  delay(3000);
  digitalWrite(LED_HIJAU, LOW);
}

void aksesGagal() {
  Serial.println("AKSES GAGAL");

  digitalWrite(LED_MERAH, HIGH);
  delay(3000);
  digitalWrite(LED_MERAH, LOW);
}

void setup() {
  Serial.begin(9600);
  SPI.begin();
  rfid.PCD_Init();

  pinMode(LED_HIJAU, OUTPUT);
  pinMode(LED_MERAH, OUTPUT);

  digitalWrite(LED_HIJAU, LOW);
  digitalWrite(LED_MERAH, LOW);

  Serial.println("READY");
}

void loop() {

  // === BACA RFID ===
  if (!rfid.PICC_IsNewCardPresent()) return;
  if (!rfid.PICC_ReadCardSerial()) return;

  uidString = "";

  for (byte i = 0; i < rfid.uid.size; i++) {
    uidString += String(rfid.uid.uidByte[i], HEX);
  }

  uidString.toUpperCase();

  Serial.print(uidString);
  Serial.println(",");
  Serial.println(LOCKER_ID);

  waitingResponse = true;

  // stop reading
  rfid.PICC_HaltA();

  // === TUNGGU RESPON DARI PYTHON ===
  unsigned long startTime = millis();

  while (waitingResponse) {
    if (Serial.available()) {
      String response = Serial.readStringUntil('\n');
      response.trim();

      if (response == "OK") {
        aksesBerhasil();
      } else if (response == "FAIL") {
        aksesGagal();
      }

      waitingResponse = false;
    }
    if (millis() - startTime > 10000) {
    Serial.println("TIMEOUT");
    waitingResponse = false;
  }
}

}