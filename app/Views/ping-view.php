<input type="checkbox" id="pc" <?= $checked; ?>>
<div class="checkbox"></div>
<script>
$("#pc").change(function() {
	post('pc');
});
</script>