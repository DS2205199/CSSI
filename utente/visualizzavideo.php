<!DOCTYPE html>
<html>
<head>
    <title>Video Player - YouTube Style</title>
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            grid-gap: 20px;
        }
        
        .video-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .video-card .video-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%; /* Rapporto 16:9 */
        }
        
        .video-card iframe,
        .video-card video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        .video-card .video-title {
            padding: 10px;
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Video Player - YouTube Style</h1>
        <div class="video-grid">
            <?php
            // Connessione al database
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "cssi";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connessione al database fallita: " . $conn->connect_error);
            }

            // Modifica il percorso della cartella "uploads"
            $videoFolder = "../uploads/";

            // Query per recuperare i video dal database
            $sql = "SELECT * FROM videos";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $videoTitle = $row['video_title'];
                    $videoUrl = $row['video_url'];
                    $videoFile = $row['video_file'];

                    // Estrai l'ID del video da URL di YouTube
                    $youtubeVideoId = getYoutubeVideoId($videoUrl);

                    echo '<div class="video-card">';
                    echo "<div class='video-container'>";
                    
                    // Visualizza il video di YouTube se è presente l'URL
                    if (!empty($youtubeVideoId)) {
                        echo "<iframe width='560' height='315' src='https://www.youtube.com/embed/$youtubeVideoId' frameborder='0' allowfullscreen></iframe>";
                    }

                    // Visualizza il video caricato se è presente il file
                    if (!empty($videoFile)) {
                        $videoPath = $videoFolder . $videoFile; // Aggiungi il percorso della cartella
                        echo "<video controls>";
                        echo "<source src='$videoPath' type='video/mp4'>";
                        echo "</video>";
                    }
                    
                    echo "</div>";
                    echo "<div class='video-title'>$videoTitle</div>";
                    echo '</div>';
                }
            } else {
                echo "<div class='col-12'>";
                echo "Nessun video presente nel database.";
                echo "</div>";
            }

            // Funzione per estrarre l'ID del video da URL di YouTube
            function getYoutubeVideoId($url) {
                $parts = parse_url($url);
                if (isset($parts['query'])) {
                    parse_str($parts['query'], $query);
                    if (isset($query['v'])) {
                        return $query['v'];
                    }
                }
                return null;
            }

            // Chiudi la connessione al database
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
