<template>
	<div>
		<div class="content">
			<div class="container-fluid">
					
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20" v-if="!loadingSpinner">
					<p class="_title0">Tags <Button @click="openAddModal"><Icon type="md-add" /> Add</Button></p>

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
									<Button type="info" size="small" @click="openEditModal(tag, index)">Edit</Button>
									<Button type="error" size="small" @click="openDeleteModal(tag, index)">Delete</Button>
									<!-- <Button type="error" size="small" @click="deleteTag(tag, index)"  :loading="tag.isDeleting">{{ tag.isDeleting ? 'Deleting..' : 'Delete'}}</Button> -->

									<!-- <button class="_btn _action_btn edit_btn1" type="button">Edit</button>
									<button class="_btn _action_btn make_btn1" type="button">Delete</button> -->
								</td>
							</tr>							
								<!-- ITEMS -->
								
						</table>
					</div>
				</div>
				<div v-else>
					<div class="lds-ripple"><div></div><div></div></div>
				</div>
				 <!-- <Page :total="100" /> -->
				 <!-- tag adding modal -->
				  	<Modal
						v-model="addModal"
						title="Add a Tag"
						:mask-closable="false"
						:closable="true"
						>
						<Input prefix="ios-link" clearable v-model="data.tagName" placeholder="Tag Name" style="width: 100%" />
						<div slot="footer">
							<Button type="default" @click="closeAddModal">close</Button>
							<Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Adding..' : 'Add'}}</Button>
						</div>

					</Modal>	
				 <!-- tag adding modal -->	
				  <!-- tag edit modal -->
				  	<Modal
						v-model="editModal"
						title="Edit Tag"
						:mask-closable="false"
						:closable="true"
						>
						<Input prefix="ios-link" clearable v-model="editData.tagName" placeholder="Tag Name" style="width: 100%" />
						<div slot="footer">
							<Button type="default" @click="closeEditModal">close</Button>
							<Button type="primary" @click="updateTag" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Updating..' : 'Update'}}</Button>
						</div>

					</Modal>	
				 <!-- tag edit modal -->	
				 <!-- delete modal -->
					<!-- <Modal v-model="deleteModal" width="360">
						<p slot="header" style="color:#f60;text-align:center">
							<Icon type="ios-information-circle"></Icon>
							<span>Delete confirmation</span>
						</p>
						<div style="text-align:center">
							<p>Are you sure you want to delete it?</p>
						</div>
						<div slot="footer">
							<Button type="error" size="large" long :loading="isAdding" :disabled="isAdding" @click="deleteTag">Delete</Button>
						</div>
					</Modal> -->
				 <!-- delete modal -->
				 <deleteModal></deleteModal>
			</div>
		</div>
	</div>
</template>
<script>
import deleteModal from '../components/deletemodal';
import { mapGetters } from 'vuex';
export default {
	components:{
		deleteModal,
	},
	data(){
		return{
			data: {
				tagName:'',
			},
			addModal: false,
			editModal: false,
			isAdding: false,
			tags:[],
			editData: {
				id:'',
				tagName:'',
			},
			editIndex:-1,
			
			// deleteModal: false,
			// deleteIndex:-1,
			// deleteData:{
			// 	id:'',
			// 	tagName:'',
			// },
			loadingSpinner: true,
		}	
	},

	computed:{
        ...mapGetters(['getDeleteModalObj'])
	},
	watch:{
		getDeleteModalObj(obj){
			console.log(obj.deleteIndex);
			console.log(this.tags);
			if(obj.isDeleted){
				this.tags.splice(obj.deleteIndex,1);
			}
		}
	},

	async created(){
		const res = await this.callApi('get','/admin/tag/get_tags');
		
		if(res.status==200){
			this.loadingSpinner=false
			this.tags = res.data
		} else {
			this.loadingSpinner=false
			this.e();
		}
	},

	methods:{

		//add Tag
		async addTag(){
			this.btnloading();
			if(this.data.tagName.trim()==''){
				this.btnloadingOff();
				return this.e('Tagname is required!');
			} 
			const res = await this.callApi('post','/admin/tag/create_tag',this.data);
			if(res.status===201){
				this.tags.unshift(res.data); //adds response to the begining of tags array
				this.btnloadingOff();
				this.s('Tag has been added successfully!');
				this.closeAddModal();
				this.clearAddModalTextField();
			} else if(res.status===422) {
				for(let i in res.data.errors) this.e(res.data.errors[i])
				this.btnloadingOff();
			} else {
				this.btnloadingOff();
                this.e();
            }
			
		},
		//update Tag
		async updateTag(){
			this.btnloading();
			if(this.editData.tagName.trim()==''){
				this.btnloadingOff();
				return this.e('Tagname is required!');
			} 
			const res = await this.callApi('post','/admin/tag/update_tag',this.editData);
			if(res.status===200){
				this.tags[this.editIndex].tagName = this.editData.tagName;
				this.btnloadingOff();
				this.s('Tag has been edited successfully!');
				this.closeEditModal();
				this.clearEditModalTextField();
			} else if(res.status===422) {
				for(let i in res.data.errors) this.e(res.data.errors[i])
				this.btnloadingOff();
			} else {
				this.btnloadingOff();
                this.e();
            }
		},
		// delete Tag
		// async deleteTag(){
		// 		this.btnloading();
		// 		const res = await this.callApi('post', '/admin/tag/delete_tag',this.deleteData);
		// 		if(res.status===200){
		// 			this.tags.splice(this.deleteIndex,1);
		// 			this.s('Tag has been deleted successfully!');
		// 			this.btnloadingOff();
		// 			this.closeDeleteModal();
		// 		} else {
		// 		this.btnloadingOff();
		// 		this.closeDeleteModal();
		// 		this.e();
		// 		}
			
		// },

		// async deleteTag(){
		// 	if(!confirm('Are You Sure About Deleting This Tag?')){

		// 	} else {
		// 		this.$set(tag, 'isDeleting', true);
		// 		const res = await this.callApi('post', '/app/delete_tag',tag);
		// 		if(res.status===200){
		// 			this.tags.splice(index,1);
		// 			this.s('Tag has been deleted successfully!');
		// 			this.$set(tag, 'isDeleting', false);
		// 		} else {
		// 		this.$set(tag, 'isDeleting', false);
		// 		this.e();
		// 		}
		// 	}
		// },

		//others
		openAddModal(){
			this.addModal = true;
		},
		closeAddModal(){
			this.addModal = false;
		},
		btnloading(){
			this.isAdding=true;
		},
		btnloadingOff(){
			this.isAdding=false;
		},
		clearAddModalTextField(){
			this.data.tagName='';
		},
		clearEditModalTextField(){
			this.editData.tagName='';
		},
		openEditModal(tag, index){	
			this.editModal=true;
			this.editData.id = tag.id;
			this.editData.tagName = tag.tagName;
			this.editIndex = index;
		},
		closeEditModal(){
			this.editData.id = '';
			this.editData.tagName = '';
			this.editModal = false;
			this.editIndex = -1;
		},
		// openDeleteModal(tag, index){
		// 	this.deleteIndex = index;
		// 	this.deleteData.id = tag.id;
		// 	this.deleteData.tagName = tag.tagName;
		// 	this.deleteModal = true;
		// },
		openDeleteModal(tag, index){
			const deleteModalObj = {
									showDeleteModal:true,
									deleteUrl:'/admin/tag/delete_tag',
									deleteIndex:index,
									isDeleted:false,
									deleteData:tag,
			}
			this.$store.commit('setDeleteModalObj', deleteModalObj);
		},
		// closeDeleteModal(){
		// 	this.deleteIndex = -1;
		// 	this.deleteData.id = '';
		// 	this.deleteData.tagName = '';
		// 	this.deleteModal = false;
		// },
		
	},
	cancel(){
			if(this.addModal==true){
				this.clearAddModalTextField();
				this.closeAddModal();
			} else if (this.editModal==true){
				this.clearEditModalTextField();
				this.closeEditModal();
			} else {

			}
    },
}
</script>




			