<?php
// Simple Blog Data (Mocking a DB)
$posts = [
    [
        'id' => 1,
        'title' => 'How to Download Instagram Reels without Watermark',
        'excerpt' => 'Learn the easiest and fastest way to download Instagram reels for offline viewing without any watermark.',
        'content' => 'Instagram Reels are everywhere, but sometimes you want to keep them on your phone. To download without a watermark, simply copy the Reel URL, paste it into our downloader, and click generate.',
        'date' => 'Oct 15, 2026',
        'thumbnail' => 'https://images.unsplash.com/photo-1611262588024-d12430b98920?w=400&q=80'
    ],
    [
        'id' => 2,
        'title' => 'Best Terabox Downloader Online: A Complete Guide',
        'excerpt' => 'Discover why our tools are the top choice for extracting files from Terabox links safely and quickly.',
        'content' => 'Terabox provides huge storage, but downloading shared files can sometimes be tricky. Using our tool, you can get direct download links for your Terabox files in a single click.',
        'date' => 'Oct 12, 2026',
        'thumbnail' => 'https://images.unsplash.com/photo-1614850523296-d8c1af93d400?w=400&q=80'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog | Media Downloader Guides</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/favicon.png?v=1.1">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="page-blog">

    <!-- Navigation -->
    <header>
        <div class="container">
            <nav>
                <a href="index.php" class="logo"><img src="assets/img/logo.png?v=1.1" alt="Logo" style="height: 40px; width: auto; border-radius: 8px;"> Media<span>Downloader</span></a>
                <ul class="nav-links">
                    <li><a href="index.php">Downloader</a></li>
                    <li><a href="playlist.php">Playlist</a></li>
                    <li><a href="converter.php">Converter</a></li>
                    <li><a href="transcript.php">Transcript</a></li>
                    <li><a href="summarizer.php">Summarizer</a></li>
                    <li><a href="tools.php">More Tools</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="container" style="padding-top: 60px;">
        <h1 class="section-title">Latest Blog Posts</h1>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px;">
            <?php foreach ($posts as $post): ?>
                <div class="glass" style="overflow: hidden; border-radius: var(--radius-md);">
                    <img src="<?= $post['thumbnail'] ?>" alt="Blog Thumbnail" style="width: 100%; height: 200px; object-fit: cover;">
                    <div style="padding: 25px;">
                        <span style="color: var(--accent-color); font-size: 0.8rem; font-weight: 600;"><?= $post['date'] ?></span>
                        <h3 style="margin: 10px 0;"><?= $post['title'] ?></h3>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 20px;"><?= $post['excerpt'] ?></p>
                        <a href="post.php?id=<?= $post['id'] ?>" class="btn-primary" style="display: inline-block; text-decoration: none;">Read More</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="ad-slot ad-native glass"> Sidebar Native Ad </div>
    </section>

    <footer>
        <div class="container text-center">
            <p>&copy; 2026 MediaDownloader Pro. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
