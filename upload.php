<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload your offer - LightWork</title>
    <link rel="stylesheet" href="assets/css/styleslucas2.css">
    <style>
        /* Additional styles specific to the form */
        .upload-container {
            background-color: var(--secondary-background);
            padding: 40px 20px;
            border-radius: 8px;
            max-width: 600px;
            margin: 40px auto;
        }

        .upload-container h2 {
            color: var(--primary-color);
            font-family: 'BNBobbieSans', Arial, sans-serif;
            text-align: center;
            margin-bottom: 30px;
        }

        .upload-container form {
            display: flex;
            flex-direction: column;
        }

        .upload-container label {
            color: var(--text-color);
            font-weight: bold;
            margin-bottom: 10px;
        }

        .upload-container input[type="text"],
        .upload-container input[type="number"],
        .upload-container textarea,
        .upload-container select {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .upload-container input[type="file"] {
            margin-bottom: 20px;
            color: var(--text-color);
        }

        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .preview-container img {
            max-width: 100px;
            max-height: 100px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .upload-container button[type="submit"] {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .upload-container button[type="submit"]:hover {
            background-color: #7a0bdb;
        }

        .alert {
            background-color: #f44336;
            color: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert a {
            color: white;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php">
                <img src="assets/images/logo lightwork v0.2.png" alt="LightWork Logo">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">What is Lightning?</a></li>
                <li><a href="upload.php">Post an offer</a></li>
                <li><a href="browse.php">Browse offers</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
                
        </nav>

        <?php
                if (isset($_SESSION["username"])){
                    echo "<div class='auth-buttons'>";
                    echo "<a href='user_profile.php' class='login-btn'>Profile</a>";
                    echo "<a href='php/LO.php' class='signup-btn'>Log out</a>";
                    echo "</div>";
                }else{
                    echo "<div class='auth-buttons'>";
                    echo "<a href='login.php' class='login-btn'>Login</a>";
                    echo "<a href='login.php' class='signup-btn'>Signup</a>";
                    echo "</div>";
                }
                ?>
    </header>

    <div class="upload-container">
        <h2>Upload Your Offer</h2>

        <?php
        if (!isset($_SESSION["username"])) {
            echo '<div class="alert">';
            echo 'You must be logged in to upload an offer. ';
            echo '<a href="login.php">Click here to log in</a>';
            echo '</div>';
        } else {
        ?>

        <form action="php/upload_be.php" method="POST" enctype="multipart/form-data">
            <label for="title">Offer Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Offer Description:</label>
            <textarea id="description" name="description" rows="5" required></textarea>

            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="" disabled selected>Select a category</option>
                <option value="Web Development">Web Development</option>
                <option value="Graphic Design">Graphic Design</option>
                <option value="SEO">SEO</option>
                <option value="Financial Advice">Financial Advice</option>
                <!-- Add more categories as needed -->
            </select>

            <label for="price">Price (USD):</label>
            <input type="number" id="price" name="price" min="0" step="1" required>

            <label for="photos">Upload Photos (up to 10):</label>
            <input type="file" id="photos" name="photos[]" accept="image/*" multiple>

            <div class="preview-container" id="preview-container"></div>

            <button type="submit">Submit Offer</button>
        </form>

        <?php
        }
        ?>

    </div>

    <script>
        // JavaScript to handle image preview and limit to 10 images
        document.getElementById('photos').addEventListener('change', function() {
            const previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = ''; // Clear previous previews

            const files = Array.from(this.files).slice(0, 10); // Limit to 10 files
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
</body>
</html>