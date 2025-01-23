# BowlScore

L'applicativo BowlScore è un sistema per la gestione e il calcolo dei punteggi del bowling.

## Istruzioni per l'esecuzione

1. Predisporre un ambiente web utilizzando **XAMPP**.
2. All'interno della cartella inviata si trova il file `create_database.sql`, che permette di creare il database già popolato con qualche dato di prova.
3. Le credenziali per accedere al database si trovano nel file `bootstrap.php`, situato nella cartella `PHP/Utils/`. Le credenziali possono essere modificate e sono le seguenti:

   ```php
   $host = "localhost";
   $user = "root";
   $password = "";
   $dbName = "bowling";
   ```

4. Per accedere all'applicativo, partire da `localhost/index.html`.

## Credenziali di accesso

- **Utente di prova**:
  - Email: `mariorossi@gmail.com`
  - Password: `11111111`

- **Amministratore**:
  - Email: `admin@gmail.com`
  - Password: `11111111`
