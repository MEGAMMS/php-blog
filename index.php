<?php
require_once 'lib/common.php';

// Connect to the database, run a query, handle errors
$pdo = getPDO();
$stmt = $pdo->query(
    'SELECT
        id, title, created_at, body
    FROM
        post
    ORDER BY
        created_at DESC'
);
if ($stmt === false)
{
    throw new Exception('There was a problem running this query');
}

$notFound = isset($_GET['not-found']);

?>
<!DOCTYPE html>
<html>

<head>
    <title>A blog application</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
</head>

<body>
    <?php require 'templates/title.php' ?>

    <?php if ($notFound): ?>
    <div style="border: 1px solid #ff6666; padding: 6px;">
        Error: cannot find the requested blog post
    </div>
    <?php endif ?>

    <section class="container">
        <div class="row">
            <main class="col">
                <div class="row" id="post_cards">

                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <article class="col-12 col-lg-6 my-3">
                        <div class="card " style="width: 18rem;">
                            <div class="card-body">
                                <h4 class="card-title">
                                    <?php echo htmlEscape($row['title']) ?>
                                </h4>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <?php echo convertSqlDate($row['created_at']) ?>
                                    (<?php echo countCommentsForPost($row['id']) ?> comments)
                                </h6>
                                <p class="card-text">
                                    <?php echo htmlEscape($row['body']) ?>
                                </p>
                                <a class="card-link" href="view-post.php?post_id=<?php echo $row['id'] ?>">Read
                                    more...</a>
                            </div>
                        </div>
                    </article>
                    <?php endwhile ?>

                </div>
            </main>
        </div>
    </section>

</body>

</html>