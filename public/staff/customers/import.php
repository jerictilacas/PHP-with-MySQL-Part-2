<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

    if (isset($_POST["import"])) {

        $fileName = $_FILES["file"]["tmp_name"];

        if ($_FILES["file"]["size"] > 0) {

            $file = fopen($fileName, "r");

            // w/ limit
            // $header = fgetcsv($file, 1000, ",");

            $header = fgetcsv($file);
            $header = array_map('trim', $header);

            while ($row = fgetcsv($file)) {

                $data = array_combine($header, array_map('trim', $row));
                $customer = array();
                $customer['first_name'] = $data['first name'];
                $customer['last_name'] = $data['last name'];
                $customer['email'] = $data['email'] ?? '';


                if(!empty($customer))
                {
                    $result = insert_customer($customer);
                }

            }

            redirect_to(url_for('/staff/customers/index.php'));
        }
    }
} else {
  // display the blank form
  $customer = [];
  $customer["first_name"] = '';
  $customer["last_name"] = '';
  $customer["email"] = '';
}

?>

<?php $page_title = 'Create Admin'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/customers/index.php'); ?>">&laquo; Back to List</a>

  <div class="admin new">
    <h1>Import Customer</h1>

    <?php echo display_errors($errors); ?>

    <form action="<?php echo url_for('/staff/customers/import.php'); ?>" enctype="multipart/form-data" method="post">
      <dl>
        <dt>Upload File</dt>
        <dd><input type="file" name="file" accept=".csv"</dd>
      </dl>

      <div id="operations">
        <input type="submit" name="import" value="Upload" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
