Nella cartella BowlScore è possibile trovare tutto il sorgente dell'applicativo, mentre per eseguirlo bisogna predisporre un ambiente web utilizzando XAMPP.\\
All'interno della cartella inviata si trova un file "create_database.sql" che permette di creare il database già popolato con qualche dato di prova.\\
Le credenziali per accedere al database si trovano nel file "bootstrap.php" nella cartella "PHP/Utils/". Le credenziali possono essere modificate e sono queste:\\
\\
\\$host = "localhost";
\\$user = "root";
\\$password = "";
\\$dbName = "bowling";