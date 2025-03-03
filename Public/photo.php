<?php require_once("../includes/initialize.php"); ?>
<?php
	// Check if the ID was saved in the $_GET global variable
	if(empty($_GET['id'])) {
		$session->message("No photograph ID was provided.");
		redirect_to('indexPictures.php');
	}
  
	// Find the photograph
	$photo = Photograph::find_by_id($_GET['id']);
	if(!$photo) {
		$session->message("The photo could not be located.");
		redirect_to('indexPictures.php');
	}
  
	// Form processing for submitting the comment
	if(isset($_POST['submit'])) {
		// If it is true, establish what the author and the body ought to be
		$author = trim($_POST['author']);
		$body = trim($_POST['body']);
	
		// The author, body and photo id used to make the comment
		$new_comment = Comment::make($photo->id, $author, $body);
		// Check if the comment was created successfully and was saved successfully 
		if($new_comment && $new_comment->save()) {
			// comment saved
			// No message needed; seeing the comment is proof enough.

			// Important!  You could just let the page render from here. 
			// But then if the page is reloaded, the form will try 
			// to resubmit the comment. So redirect instead:
			redirect_to("photo.php?id={$photo->id}");
			
			// If we try to display our comment here, we would not get to see it because of the redirect.
		} else {
			// Failed
			$message = "There was an error that prevented the comment from being saved.";
		}
	} else {
		$author = "";
		$body = "";
	}
	// This is the preferred place to place the comment. so when even after the web page is redirected, you will be able to see the comments.
	// We need to find all the comments, put them in an array and assign it to a variable. Next we need to display the comments by looping through the array and displaying the comments each one at a time.
	// This should return an array for the comment.
	$comments = $photo->comments();
?>
<?php include_layout_template('header.php'); ?>

<a href="indexPictures.php">&laquo; Back</a><br />
<br />

<!-- Display the pictures -->
<div style="margin-left: 20px;">
  <img src="<?php echo $photo->image_path(); ?>" />
  <p><?php echo $photo->caption; ?></p>
</div>

<!-- Here we would display the comments. Firstly, we would loop through the comment array and display them one after the other. -->
<div id="comments">
  <?php foreach($comments as $comment): ?>
    <div class="comment" style="margin-bottom: 2em;">
	    <div class="author">
	      <?php echo htmlentities($comment->author); ?> wrote:
		</div>
		<div class="body">
			<?php 
			// The strip_tags will not allow any other HTML tags to be used for formating except these tags <strong><em><p>
			echo strip_tags($comment->body, '<strong><em><p>'); 
			?>
		</div>
		<div class="meta-info" style="font-size: 0.8em;">
	      <?php echo datetime_to_text($comment->created); ?>
	    </div>
    </div>
  <?php endforeach; ?>
  <!-- If no comment, display ther is none -->
  <?php if(empty($comments)) { echo "No Comments."; } ?>
</div>

<!-- Display the form for the comments. -->
<div id="comment-form">
  <h3>New Comment</h3>
  <?php echo output_message($message); ?>
  <form action="photo.php?id=<?php echo $photo->id; ?>" method="post">
    <table>
      <tr>
        <td>Your name:</td>
        <td><input type="text" name="author" value="<?php echo $author; ?>" /></td>
      </tr>
      <tr>
        <td>Your comment:</td>
        <td><textarea name="body" cols="40" rows="8"><?php echo $body; ?></textarea></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" value="Submit Comment" /></td>
      </tr>
    </table>
  </form>
</div>

<?php include_layout_template('footer.php'); ?>
