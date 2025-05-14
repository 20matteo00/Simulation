<div class="container py-5">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php
        $modes = [
            [
                'key' => 'round_robin',
                'title' => $langfile['round_robin'],
                'description' => $langfile['round_robin_desc']
            ],
            [
                'key' => 'swiss_system',
                'title' => $langfile['swiss_system'],
                'description' => $langfile['swiss_system_desc']
            ],
            [
                'key' => 'knockout_single',
                'title' => $langfile['knockout_single'],
                'description' => $langfile['knockout_single_desc']
            ],
            [
                'key' => 'knockout_double',
                'title' => $langfile['knockout_double'],
                'description' => $langfile['knockout_double_desc']
            ],
            [
                'key' => 'group_and_knockout',
                'title' => $langfile['group_and_knockout'],
                'description' => $langfile['group_and_knockout_desc']
            ],
            [
                'key' => 'playoff_system',
                'title' => $langfile['playoff_system'],
                'description' => $langfile['playoff_system_desc']
            ]
        ];

        foreach ($modes as $mode) :
        ?>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm transition-mode">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold text-uppercase mb-3">
                            <?= htmlspecialchars($mode['title']) ?>
                        </h5>
                        <p class="card-text mode-description d-none">
                            <?= htmlspecialchars($mode['description']) ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>