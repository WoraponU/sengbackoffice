<template>
	<div id="modalDelete" class="modal left-align">
		<form :action="action" method="POST">
            <input type="hidden" name="_token" v-model="csrfToken">
            <input type="hidden" name="_method" value="DELETE">
			<div class="modal-content">
				<h4>ลบข้อมูลรถบรรทุก</h4>
				<div class="divider"></div>
				<p>&emsp;{{ licensePlate }}</p>
			</div>
			<div class="modal-footer">
				<button type="submit" class="modal-action waves-effect waves-green btn-flat">ลบ</button>
				<button type="button" class="modal-close modal-action waves-effect waves-green btn-flat">ปิด</button>
			</div>
		</form>
	</div>
</template>

<script>
    import axios from 'axios';
    export default {
        props: {
            id: { require: true },
            licensePlate: { require: true },
        },
		data() {
            return {
                action: '/backoffice/truck/' + this.id,
				csrfToken: window.Laravel.csrfToken,
            }
        },
    }
</script>