document.addEventListener('DOMContentLoaded', function(){
  // Funktion für "Mehr laden" (optional, falls noch nicht implementiert)
  document.querySelectorAll('.btn-load-more').forEach(function(button) {
    button.addEventListener('click', function() {
      const container = button.closest('.va-tag__content');
      if (container) {
        container.querySelector('.va-tag__content-preview').style.display = 'none';
        container.querySelector('.va-tag__content-full').style.display = 'block';
      }
    });
  });

  // Funktion für "Schließen" (btn-show-less), um zur Vorschau zurückzukehren
  document.querySelectorAll('.btn-show-less').forEach(function(button) {
    button.addEventListener('click', function() {
      const container = button.closest('.va-tag__content');
      if (container) {
        container.querySelector('.va-tag__content-full').style.display = 'none';
        container.querySelector('.va-tag__content-preview').style.display = 'block';
      }
    });
  });
});