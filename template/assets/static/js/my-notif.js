  /* Default Notifications */

const flashData = $('.flash-data').data('flashdata');
const flashDataError = $('.flash-data-error').data('flashdata');

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

// if (flashData) {
// 	Swal.fire({
// 		position: 'top-end',
// 		icon: 'success',
// 		title: flashData,
// 		showConfirmButton: false,
// 		timer: 1500
// 	})
// }
// if (flashDataError) {
// 	Swal.fire({
// 		icon: 'error',
// 		title: 'Error',
// 		text: flashDataError
// 	})
// }

if (flashData) {
	Toast.fire({
    	icon: 'success',
    	title: flashData
  	})
}
if (flashDataError) {
	Toast.fire({
    	icon: 'error',
    	title: flashDataError
  	})
}

$('.tombol-hapus').on('click', function (e) {
	
	e.preventDefault();
	const hreh = $(this).attr('href');

	Swal.fire({
		title: 'Yakin ?',
		text: 'Data ini akan dihapus permanent',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Hapus Data!',
		
	}).then((result) => {
		if (result.value) {
			document.location.href = hreh
		}
	});
	
});

$('.tbl-confirm').on('click', function (e) {
	
	e.preventDefault();
	const hreh = $(this).attr('href');
	const isi = $(this).attr('value');

	Swal.fire({
		title: 'Yakin ?',
		text: isi,
		icon: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Lanjutkan!',
		
	}).then((result) => {
		if (result.value) {
			document.location.href = hreh
		}
	});
	
});