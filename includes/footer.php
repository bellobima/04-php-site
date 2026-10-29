    </main>
    <footer class="site-footer">
        <div class="container footer-content">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?>. All rights reserved.</p>
            <a href="mailto:<?= htmlspecialchars($contactEmail, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($contactEmail, ENT_QUOTES, 'UTF-8') ?>
            </a>
        </div>
    </footer>
</body>
</html>
