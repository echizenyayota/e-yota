<footer>
<!-- カテゴリーメニュ -->
  <div class="footer-inner">
    <aside class="footer-category">
      <ul>
        <?php dynamic_sidebar('フッター1'); ?>
      </ul>
    </aside>
  </div>
    <div class="copyright">
      <div class="footer-inner">
        <p><?php bloginfo( 'description' ); ?></p>
        <p>Copyright &copy; <?php bloginfo( 'name' ); ?></p>
      </div>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
