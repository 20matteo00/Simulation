<footer class="bg-dark text-white pt-4 pb-2 mt-5">
    <div class="container">
        <div class="row">
            <!-- Colonna 1 -->
            <div class="col-md-6 mb-3">
                <h5><?= $langfile['contact'] ?></h5>
                <div class="row">
                    <div class="col-4 fw-bold"><?= $langfile['tel'] ?>:</div>
                    <div class="col-8"><a href="tel:+123456789" class="text-decoration-none">+123456789</a></div>
                </div>
                <div class="row">
                    <div class="col-4 fw-bold"><?= $langfile['email'] ?>:</div>
                    <div class="col-8"><a href="mailto:info@example.com" class="text-decoration-none">info@example.com</a></div>
                </div>
                <div class="row">
                    <div class="col-4 fw-bold"><?= $langfile['address'] ?>:</div>
                    <div class="col-8">123 Main St, City, Country</div>
                </div>

            </div>

            <!-- Colonna 2 -->
            <div class="col-md-2 mb-3">
                <h5><?= $langfile['links'] ?></h5>
                <ul class="list-unstyled">
                    <?php foreach ($help->menu as $m): ?>
                        <li class="nav-item">
                            <a class="text-white text-decoration-none" aria-current="page" href="?page=<?= $m ?>"><?= $langfile[$m] ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Colonna 3 -->
            <div class="col-md-4 mb-3">
                <h5><?= $langfile['social'] ?></h5>
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="#" class="text-white">
                            <i class="bi bi-facebook fs-4"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="text-white">
                            <i class="bi bi-discord fs-4"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="text-white">
                            <i class="bi bi-twitter-x fs-4"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="text-white">
                            <i class="bi bi-instagram fs-4"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="text-white">
                            <i class="bi bi-threads fs-4"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>