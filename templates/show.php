<?php $title = $job['position'] ?>

<?php ob_start() ?>
    <div class="container">
        <a class="btn btn-lg btn-success" role="button" href="/index.php/edit">Edit</a>

        <h2><?php echo $job['position']; ?> (<?php echo $job['date']; ?>)</h2>

        <?php foreach ($job['sections'] as $key => $section): ?>
            <div>
                <h3><?php echo $key; ?></h3>

                <?php foreach ($section as $option): ?>
                    <div class="row">
                        <div class="col-md-4">
                            <b><?php echo $option[0]; ?>:</b>
                        </div>
                        <div class="col-md-8">
                            <?php echo $option[1]; ?>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endforeach ?>

    </div>
<?php $content = ob_get_clean() ?>

<?php include 'layout.php' ?>