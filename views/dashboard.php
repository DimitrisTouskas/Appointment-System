<?php require __DIR__ . '/layout/header.php'; ?>
<?php /** @var array $articles */ ?>
<main>
    <div class="row row-cols-1 row-cols-md-3 g-3">
        <?php foreach ($articles as $article): ?>
            <div class="col">
                <div class="card">
                    <div class="card-body">                
                        <img class="card-img-top" src="<?= htmlspecialchars($article['urlToImage']??'https://placehold.co/400x180?text=No+Image') ?>">
                        <h5><?= htmlspecialchars($article['title'] ) ?></h5>
                        <p> <?= htmlspecialchars($article['description']??'' ) ?> </p>
                        <span class="badge bg-secondary"><?= htmlspecialchars($article['source']['name']) ?></span>
                        <a href="<?= htmlspecialchars($article['url']) ?>">Read More</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
         
        
</main>
<?php require __DIR__ . '/layout/footer.php'; ?>