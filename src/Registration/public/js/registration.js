$(document).ready(function () {
	$('.form-submit').click(function () {
		$('form[data-xhr="true"]').submit();
	});

	RegistrationCalculation();
	$('.registration-quantity').change(function () {
		RegistrationCalculation();
	});
});

var RegistrationCalculation = function () {
	var total = 0;
	$('.registration-quantity').each(function () {
		total += $(this).attr('data-price') * $(this).val();
	});
	$('.registration-total').html(total);
};