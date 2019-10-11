<template>
	<div>
		<h3 class="mb-4">Add Client</h3>
		<div class="card">
			<div class="card-body">
				<add-client-form @updateSuccess="updateSuccess"></add-client-form>
			</div>
		</div>
	</div>
</template>

<script>
	import AddClientForm from './AddClientForm.vue'

	export default {
		components: {
			'add-client-form': AddClientForm
		},
		methods: {
			updateSuccess() {
				this.$router.push({name: 'client'});
			}
		}
	}
</script>
