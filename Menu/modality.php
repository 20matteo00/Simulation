<div class="container py-5">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($help->modality as $title => $desc): ?>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm transition-mode">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold text-uppercase mb-3">
                            <?= htmlspecialchars($langfile[$title]) ?> <!-- Correzione qui -->
                        </h5>
                        <div class="card-text mode-description d-none">
                            <p>
                                <?= htmlspecialchars($langfile[$desc]) ?> <!-- Correzione qui -->
                            </p>
                            <a href="?page=competition&mod=<?= $title ?>" class="btn btn-success mt-2">
                                <?= htmlspecialchars($langfile['test']) ?>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>