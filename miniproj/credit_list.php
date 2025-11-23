<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #fff; margin: 0; padding: 20px; text-align: center; }
        ul { list-style-type: none; padding: 0; margin: 0; }
        li { background: #f9f9f9; border-bottom: 1px solid #eee; padding: 15px; margin-bottom: 10px; border-radius: 5px; }
        h3 { margin: 0 0 5px 0; color: #333; }
        p { margin: 0; color: #777; font-size: 0.9em; }
        .role { color: #3498db; font-weight: bold; font-size: 0.8em; text-transform: uppercase; }
    </style>
</head>
<body>
    <?php
        $xml = simplexml_load_file('developer_data.xml');
    ?>

    <ul>
        <?php foreach ($xml->developer as $dev): ?>
            <li>
                <h3><?php echo htmlspecialchars($dev->name); ?></h3>
                <span class="role"><?php echo htmlspecialchars($dev->role); ?></span>
                <p>ID: <?php echo htmlspecialchars($dev->id); ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>