import Swal from 'sweetalert2';

export function confirmDelete(deleteItem: () => void, options?: object) {
    return Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#1B7B7D',
        cancelButtonColor: '#B8311D',
        confirmButtonText: 'Yes, delete it!',
        ...options
    }).then((result) => {
        if (result.isConfirmed) {
            deleteItem();
        }
    });
}
