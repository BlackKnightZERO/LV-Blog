<template>
	<div>
		<div class="content">
			<div class="container-fluid">
					
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Categories <Button @click="openAddModal"><Icon type="md-add" /> Add</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Category Name</th>
								<th>Created At</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->
								<!-- ITEMS -->
							
							<tr v-for="(category, index) in categories" :key="index">
								<td>{{ index+1 }}</td>
								<td class="_table_name">{{ category.categoryName }}</td>
								<td>{{ category.created_at }}</td>
								<td>
									<Button type="info" size="small" @click="openEditModal(category, index)">Edit</Button>
									<Button type="error" size="small" @click="openDeleteModal(category, index)">Delete</Button>
								</td>
							</tr>							
								<!-- ITEMS -->
								
						</table>
					</div>
				</div>
				 <!-- <Page :total="100" /> -->
				 <!-- Category adding midal -->
				  	<Modal
						v-model="addModal"
						title="Add a Category"
						:mask-closable="false"
						:closable="false"
						>
						<Input prefix="ios-link" clearable v-model="data.categoryName" placeholder="Category Name" style="width: 100%" />
                        <br>
                        <br>
                        <!-- <div class="space"></div> -->
                        <Upload
                            type="drag"
                            :headers="{'x-csrf-token': token}"
							:on-success="handleSuccess"
							:format="['jpg','jpeg','png']"
							:max-size="2048"
							:on-format-error="handleFormatError"
							:on-exceeded-size="handleMaxSize"
                            action="/app/category/image_upload">
                            <div style="padding: 20px 0">
                                <Icon type="ios-cloud-upload" size="32" style="color: #3399ff"></Icon>
                                <p>Click or drag files here to upload</p>
                            </div>
                        </Upload>
						<div slot="footer">
							<Button type="default" @click="closeAddModal">close</Button>
							<Button type="primary" @click="addCategory" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Adding..' : 'Add'}}</Button>
						</div>

					</Modal>	
				 <!-- Category adding midal -->	
				  <!-- Category edit midal -->
				  	<Modal
						v-model="editModal"
						title="Edit Category"
						:mask-closable="false"
						:closable="false"
						>
						<Input prefix="ios-link" clearable v-model="editData.categoryName" placeholder="Category Name" style="width: 100%" />
						<div slot="footer">
							<Button type="default" @click="closeEditModal">close</Button>
							<Button type="primary" @click="updateCategory" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Updating..' : 'Update'}}</Button>
						</div>

					</Modal>	
				 <!-- Category edit midal -->	
				 <!-- delete modal -->
					<Modal v-model="deleteModal" width="360">
						<p slot="header" style="color:#f60;text-align:center">
							<Icon type="ios-information-circle"></Icon>
							<span>Delete confirmation</span>
						</p>
						<div style="text-align:center">
							<p>Are you sure you want to delete it?</p>
						</div>
						<div slot="footer">
							<Button type="error" size="large" long :loading="isAdding" :disabled="isAdding" @click="deleteCategory">Delete</Button>
						</div>
					</Modal>
				 <!-- delete modal -->

			</div>
		</div>
	</div>
</template>

<script>
export default {
	data(){
		return{
			data: {
				iconImage:'',
				categoryName:'',
            },
            categories:[],
			addModal: false,
            isAdding: false,
            editModal: false,
			editData: {
				id:'',
				iconImage:'',
				categoryName:'',
			},
            editIndex:-1,
			deleteModal: false,
			deleteIndex:-1,
			deleteData:{
				id:'',
				categoryName:'',
            },

            token:'',
		}	
	},

	async created(){
        this.token = window.Laravel.csrfToken; //get csrf token from window.laravel object defined at welcome blade page

		const res = await this.callApi('get','/app/get_tags');
		console.log('running');
		console.log(res);
		
		if(res.status==200){
			this.categories = res.data
		} else {
			console.log('running-> ');
			this.e();
		}
	},

	methods:{

		//add Category
		async addCategory(){
			this.btnloading();
			if(this.data.categoryName.trim()==''){
				this.btnloadingOff();
				return this.e('Category name is required!');
			} 
			const res = await this.callApi('post','/app/create_tag',this.data);
			if(res.status===201){
				this.categories.unshift(res.data); //adds response to the begining of tags array
				this.btnloadingOff();
				this.s('Category has been added successfully!');
				this.closeAddModal();
				this.clearAddTextField();
			} else {
				if(res.status===422){
					if(res.data.errors.categoryName){
						this.i(res.data.errors.categoryName[0]);
					}
				} else {
				this.btnloadingOff();
				this.e();
				}
			}
			
		},
		//update Category
		async updateCategory(){
			this.btnloading();
			if(this.editData.categoryName.trim()==''){
				this.btnloadingOff();
				return this.e('Category name is required!');
			} 
			const res = await this.callApi('post','/app/update_tag',this.editData);
			if(res.status===200){
				this.categories[this.editIndex].categoryName = this.editData.categoryName;
				this.btnloadingOff();
				this.s('Category has been edited successfully!');
				this.closeEditModal();
				this.clearEditTextField();
			} else {
				if(res.status===422){
					if(res.data.errors.categoryName){
						this.i(res.data.errors.categoryName[0]);
					}
				} else {
				this.btnloadingOff();
				this.e();
				}
			}
		},
		// delete Category
		async deleteCategory(){
				this.btnloading();
				const res = await this.callApi('post', '/app/delete_tag',this.deleteData);
				if(res.status===200){
					this.category.splice(this.deleteIndex,1);
					this.s('Category has been deleted successfully!');
					this.btnloadingOff();
					this.closeDeleteModal();
				} else {
				this.btnloadingOff();
				this.closeDeleteModal();
				this.e();
				}
			
		},

		//image verification 
		handleSuccess (res, file) {
			this.data.iconImage = res
		},
		handleFormatError (file) {
			this.$Notice.warning({
				title: 'The file format is incorrect',
				desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
			});
		},
		handleMaxSize (file) {
			this.$Notice.warning({
				title: 'Exceeding file size limit',
				desc: 'File  ' + file.name + ' is too large, no more than 2M.'
			});
		},

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
		clearAddTextField(){
			this.data.categoryName='';
		},
		clearEditTextField(){
			this.editData.categoryName='';
		},
		openEditModal(category, index){	
			this.editModal=true;
			this.editData.id = category.id;
			this.editData.categoryName = category.categoryName;
			this.editIndex = index;
		},
		closeEditModal(){
			this.editData.id = '';
			this.editData.categoryName = '';
			this.editModal = false;
			this.editIndex = -1;
		},
		openDeleteModal(category, index){
			this.deleteIndex = index;
			this.deleteData.id = category.id;
			this.deleteData.categoryName = category.categoryName;
			this.deleteModal = true;
		},
		closeDeleteModal(){
			this.deleteIndex = -1;
			this.deleteData.id = '';
			this.deleteData.categoryName = '';
			this.deleteModal = false;
		},
		
	},
}
</script>