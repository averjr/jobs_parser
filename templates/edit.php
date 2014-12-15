<?php $title = "Edit | " . $job['position'] ?>

<?php ob_start() ?>
    <form action="/" method="post" name="jobform">
        <div class="container">
            <a class="btn btn-lg btn-success" role="button" href="javascript: submitform();">Save</a>

            <h2><?php echo $job['position']; ?> (<?php echo $job['date']; ?>)</h2>

            <?php foreach ($job['sections'] as $name => $section): ?>
                <div>
                    <h3><?php echo $name; ?></h3>

                    <?php foreach ($section as $option): ?>
                        <div class="row">
                            <div class="col-md-4">
                                <b><input type="text" name="name<?php echo $option[2]; ?>"
                                          value="<?php echo $option[0]; ?>">:</b>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="value<?php echo $option[2]; ?>"
                                       value="<?php echo $option[1]; ?>">
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endforeach ?>

        </div>
    </form>
<?php $content = ob_get_clean() ?>

<?php include 'layout.php' ?>