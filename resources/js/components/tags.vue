<template>
	<div>
		<div class="content">
			<div class="container-fluid">
					
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Tags <Button @click="addModal = true"><Icon type="md-add" /> Add</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Tag Name</th>
								<th>Created At</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->
								<!-- ITEMS -->
							
							<tr v-for="(tag, index) in tags" :key="index">
								<td>{{ index+1 }}</td>
								<td class="_table_name">{{ tag.tagName }}</td>
								<td>{{ tag.created_at }}</td>
								<td>
									<Button type="info" size="small">Edit</Button>
									<Button type="error" size="small">Delete</Button>

									<!-- <button class="_btn _action_btn edit_btn1" type="button">Edit</button>
									<button class="_btn _action_btn make_btn1" type="button">Delete</button> -->
								</td>
							</tr>							
								<!-- ITEMS -->
								
						</table>
					</div>
				</div>
				 <!-- <Page :total="100" /> -->
				 <!-- tag adding midal -->
				  	<Modal
						v-model="addModal"
						title="Add a Tag"
						:mask-closable="false"
						:closable="false"
						>
						<Input prefix="ios-link" clearable v-model="data.tagName" placeholder="Tag Name" style="width: 100%" />
						<div slot="footer">
							<Button type="default" @click="closeModal">close</Button>
							<Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Adding..' : 'Add'}}</Button>
						</div>

					</Modal>	
				 <!-- tag adding midal -->	

			</div>
		</div>
	</div>
</template>

<script>
export default {
	data(){
		return{
			data: {
				tagName:'',
			},
			addModal: false,
			isAdding: false,
			tags:[],
		}	
	},

	async created(){
		const res = await this.callApi('get','/app/get_tags');
		
		if(res.status==200){
			this.tags = res.data
		} else {
			console.log('running-> ');
			this.e();
		}
	},

	methods:{
		async addTag(){
			this.btnloading();
			if(this.data.tagName.trim()==''){
				this.btnloadingOff();
				return this.e('Tagname is required!');
			} 
			const res = await this.callApi('post','/app/create_tag',this.data);
			if(res.status===201){
				this.tags.unshift(res.data); //adds response to the begining of tags array
				this.btnloadingOff();
				this.s('Tag has been added successfully!');
				this.closeModal();
				this.clearTextField();
			} else {
				this.btnloadingOff();
				this.e();
			}
			
		},
		closeModal(){
			this.addModal= false;
		},
		btnloading(){
			this.isAdding=true;
		},
		btnloadingOff(){
			this.isAdding=false;
		},
		clearTextField(){
			this.data.tagName='';
		}
		
	},
}
</script>




			