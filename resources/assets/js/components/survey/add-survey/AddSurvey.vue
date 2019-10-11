<template>
	<div>
		<h3 class="mb-4">Add Survey</h3>
		<div class="card">
			<div class="card-body">
				<add-survey-form @updateSuccess="updateSuccess"></add-survey-form>
			</div>
		</div>
	</div>
</template>

<script>
	import AddSurveyForm from './AddSurveyForm.vue'

	export default {
		components: {
			'add-survey-form': AddSurveyForm
		},
		methods: {
			updateSuccess() {
				this.$router.push({name: 'survey'});
			}
		}
	}
</script>
