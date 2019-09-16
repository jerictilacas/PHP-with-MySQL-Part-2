<?php

require_once('../../../private/initialize.php');

require_login(); 


$customer_set = find_all_customers();

if(isset($_GET['export']) && $_GET['export'])
{
    $filename = 'sample.csv';

    // file creation
    $file = fopen($filename,"w");
    fputcsv($file, array('Email', 'First name', 'Last Name'));

    while ($customer = mysqli_fetch_assoc($customer_set)){
        fputcsv($file, array(
                $customer['email'],
                $customer['first_name'],
                $customer['last_name'],
        ));
    }

    fclose($file);

    // download
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=".$filename);
    header("Content-Type: application/csv; ");

    readfile($filename);

    // deleting file
    unlink($filename);

    exit;
}

?>

<?php $page_title = 'Admins'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <div class="admins listing">
    <h1>Customers</h1>

    <div class="actions">
      <a class="action" href="<?php echo url_for('/staff/customers/import.php'); ?>">Import</a>
    </div>

    <table class="list">
      <tr>
        <th>ID</th>
        <th>First</th>
        <th>Last</th>
        <th>Email</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>

      <?php while($customer = mysqli_fetch_assoc($customer_set)) { ?>
        <tr>
          <td><?php echo h($customer['id']); ?></td>
          <td><?php echo h($customer['first_name']); ?></td>
          <td><?php echo h($customer['last_name']); ?></td>
          <td><?php echo h($customer['email']); ?></td>
          <td><a class="action" href="<?php echo url_for('/staff/customer/edit.php?id=' . h(u($customer['id']))); ?>">Edit</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/customer/delete.php?id=' . h(u($customer['id']))); ?>">Delete</a></td>
        </tr>
      <?php } ?>
    </table>
      <a class="action" href="<?php echo url_for('/staff/customers/index.php?export=1'); ?>">Export to CSV</a>
    <?php
      mysqli_free_result($customer_set);
    ?>
  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
