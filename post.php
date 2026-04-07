<?php
// Simple Blog Data (Mocking a DB) - In a real setup this would be in a DB
$posts = [
    1 => [
        'title' => 'How to Download Instagram Reels without Watermark',
        'content' => 'Instagram Reels are everywhere, but sometimes you want to keep them on your phone. To download without a watermark, simply copy the Reel URL, paste it into our downloader, and click generate. Our tool extracts high-quality video links directly from Instagram servers, ensuring you get the best resolution possible. Unlike other methods, we do not add any branding or watermarks to your downloads.',
        'date' => 'Oct 15, 2026',
        'thumbnail' => 'https://images.unsplash.com/photo-1611262588024-d12430b98920?w=800&q=80'
    ],
    2 => [
        'title' => 'Best Terabox Downloader Online: A Complete Guide',
        'content' => 'Terabox provides huge storage, but downloading shared files can sometimes be tricky. Using our tool, you can get direct download links for your Terabox files in a single click. Simply enter the public link, and our extractor will do the rest. This tool is perfect for users who want to avoid installing extra apps or plugins to retrieve their cloud-stored media.',
        'date' => 'Oct 12, 2026',
        'thumbnail' => 'https://images.unsplash.com/photo-1614850523296-d8c1af93d400?w=800&q=80'
    ]
];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = isset($posts[$id]) ? $posts[$id] : null;

if (!$post) {
    header("Location: blog.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $post['title'] ?> | Media Downloader Blog</title>
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
                    <li><a href="summarizer.php">Summarizer</a></li>
                    <li><a href="tools.php">More Tools</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container" style="padding-top: 60px; max-width: 800px;">
        <article class="glass" style="padding: 40px; border-radius: var(--radius-lg);">
            <img src="<?= $post['thumbnail'] ?>" alt="Blog Thumbnail" style="width: 100%; border-radius: 12px; margin-bottom: 25px;">
            <span style="color: var(--accent-color); font-weight: 600; font-size: 0.9rem;"><?= $post['date'] ?></span>
            <h1 style="font-size: 2.5rem; margin-top: 10px; margin-bottom: 20px;"><?= $post['title'] ?></h1>
            <div style="font-size: 1.1rem; line-height: 1.8; color: var(--text-secondary);">
                <?= nl2br($post['content']) ?>
            </div>
            
            <div class="ad-slot ad-native glass" style="margin-top: 40px;"> Native Content Ad </div>
            
            <div style="margin-top: 40px; text-align: center;">
                <a href="blog.php" class="btn-primary" style="text-decoration: none;">&larr; Back to Blog Listing</a>
            </div>
        </article>
    </div>

    <footer>
        <div class="container text-center">
            <p>&copy; 2026 MediaDownloader Pro. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
