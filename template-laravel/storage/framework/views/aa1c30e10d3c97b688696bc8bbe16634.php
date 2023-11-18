<?php $__env->startSection('title', 'Cards'); ?>

<?php $__env->startSection('content'); ?>

<section id="cards">
    <?php echo $__env->renderEach('partials.card', $cards, 'card'); ?>
    <article class="card">
        <form class="new_card">
            <input type="text" name="name" placeholder="new card">
        </form>
    </article>
</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/manu/UNI/3_ano/LBAW-feup/template-laravel/resources/views/pages/cards.blade.php ENDPATH**/ ?>