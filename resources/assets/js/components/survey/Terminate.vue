<template>
	<div>
		
		<div class="card">
			<table class="table" style='height:400px;'>
			  <thead>
			    <tr>
			      <td style='text-align:center;'>Sorry, you couldn't qualify exact criteria for this survey </td>
			     
			    </tr>
			  </thead>
			 
			</table>

		</div>
	</div>
</template>

<script>
	import {mapState} from 'vuex'
	import {api} from "../../config";

	export default {
		data() {
			return {
				surveys : [],
				host:''
			};
		},
		computed: mapState({
			user: state => state.auth
		}),
		mounted() {
			
			
		},
		methods: {
			
		}
	}
</script>
