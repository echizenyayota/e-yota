<footer>
<!-- 月別の記事 -->
  <div class="footer-inner">
    <aside>
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
</footer>
<?php wp_footer(); ?>
</body>
</html>
