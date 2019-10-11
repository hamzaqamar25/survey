<template>
	<div>
		<h3 class="mb-4">Survey Report Details</h3>
		
		<div class="card" v-if='surveysdata.length > 0'>
		
			<table class="table" id='tableWithData'>
			  <thead>
			    <tr>
			      <th scope="col">SR. No.</th>
			      <th scope="col">IP</th>
			      <th scope="col" class='display:none;'>User Agent</th>
			      <th scope="col" class='display:none;'>User Referer</th>
			      <th scope="col">Click Time</th>
			      <th scope="col"> Finish Time</th>
			    </tr>
			  </thead>
			  <tbody>
			    <tr v-for='(survey,key) in surveysdata'>
			      <th scope="row">{{key + 1}}</th>
			      <th scope="row">{{survey.ip}}</th>
			      <th scope="row" class='display:none;'>{{survey.user_agent}}</th>
			      <th scope="row" class='display:none;'>{{survey.user_referer}}</th>
			      <td scope="row">{{survey.action_time}}</td>
			      <td scope="row">{{survey.final_time}}</td>
			    </tr>
			  </tbody>
			</table>

		</div>
	</div>
</template>

<script>
	import {mapState} from 'vuex'
	import {api} from "../../../config";

	export default {
		data() {
			return {
				allsurveys : [],
				surveys : [],
				loading: false,
				surveysdata : [],
				form: {
					sid: 0,
					pid: 0,
					action_date: '',
					type: 'success'
				},
			};
		},
		computed: mapState({
			user: state => state.auth
		}),
		mounted() {
			if(this.$route.params.sid) {
				this.form.sid = this.$route.params.sid;
			}
			if(this.$route.params.pid) {
				this.form.pid = this.$route.params.pid;
			}
			if(this.$route.params.sid) {
				this.form.action_date = this.$route.params.action_date;
			} 
			if(this.$route.params.type) {
				this.form.type = this.$route.params.type;
			}
			this.getSurveyData();
		},
		methods: {
			getSurveyData() {
				this.surveysdata = [];
				this.loading = true;
				axios.post(api.getSurveyDetailReport, this.form)
					.then((res) => {
						this.loading = false;
						this.surveysdata = res.data.items;
					});

				setTimeout(function(){
		            this.bindDatatable();
		          }.bind(this),100);
			},	

		 bindDatatable(){          
       
         var table = $('#tableWithData');
         var settings = {            
            fixedHeader: {header: true},
             dom: 'Bfrtip',
             retrieve: true,
             paginate: false,
             "order": [[ 1, "desc" ]],
             "language": {
                   "emptyTable": this.$t("No_data_available")
                },
             buttons: [
              {
                 extend: 'csv',
                 className: 'btn btn-complete btn-cons pull-left',
                 text : "Download Data",
                                 
                 filename: function(){                  
                     return 'csv_report';
                 }
             },
         ]
      };
        this.table = table.dataTable(settings);
       
    }, 
		}
	}
</script>
