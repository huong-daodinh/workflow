import Swal from 'sweetalert2';
export function showMessage(msg = '', type = 'success') {
  const toast: any = Swal.mixin({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 3000,
    customClass: { container: 'toast' }
  });
  toast.fire({
    icon: type,
    title: msg,
    padding: '10px 20px'
  });
}
