<li class="item" data-id="<?php echo e($item->id); ?>">
    <label>
        <input type="checkbox" <?php echo e($item->done?'checked':''); ?>>
        <span><?php echo e($item->description); ?></span>
        <a href="#" class="delete">&#10761;</a>
    </label>
</li><?php /**PATH /home/manu/UNI/3_ano/LBAW-feup/template-laravel/resources/views/partials/item.blade.php ENDPATH**/ ?>