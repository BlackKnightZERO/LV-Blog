<template>
	<div>
		<div class="content">
			<div class="container-fluid">
					
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20" v-if="!loadingSpinner">
					<p class="_title0">Users <Button @click="openAddModal"><Icon type="md-add" /> Add</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
							<tr>
								<th>Sl</th>
								<th>FullName</th>
								<th>E-Mail</th>
								<th>Type</th>
								<th>Created At</th>
								<th>Action</th>
							</tr>
							<tr v-for="(user, index) in users" :key="index">
								<td>{{ index+1 }}</td>
								<td class="_table_name">{{ user.fullName }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.userType }}</td>
								<td>{{ user.created_at }}</td>
								<td>
									<Button type="info" size="small" @click="openEditModal(tag, index)">Edit</Button>
									<Button type="error" size="small" @click="openDeleteModal(tag, index)">Delete</Button>
								</td>
							</tr>									
						</table>
					</div>
				</div>
                <div v-else>
					<div class="lds-ripple"><div></div><div></div></div>
				</div>
				 <!-- Add modal -->
				  	<Modal
						v-model="addModal"
						title="Add User"
						:mask-closable="false"
						:closable="true"
						>
                        <div class="spacer">
                            <Input prefix="ios-contact" type="text" clearable v-model="data.fullName" placeholder="Full Name" style="width: 100%" />
                        </div>
						 <div class="spacer">
                             <Input prefix="ios-mail-outline" type="email" clearable v-model="data.email" placeholder="E-Mail" style="width: 100%" />
                         </div>
						 <div class="spacer">
                             <Input prefix="ios-lock" type="password" clearable v-model="data.password" placeholder="Password" style="width: 100%" />
                         </div>
						 <div class="spacer">
                             <Layout style="background:#ffffff!important;">
                                 <Content style="margin: auto 16px;text-align: right;"></Content>
                                    <Sider hide-trigger>
                                        <Select prefix="md-paper-plane" v-model="data.userType"  placeholder="User type">
                                            <Option value="2">Editor</Option>
                                            <Option value="1">Admin</Option>
                                        </Select>
                                    </Sider>
                             </Layout>
                         </div>
						<div slot="footer">
							<Button type="default" @click="closeAddModal">close</Button>
							<Button type="primary" @click="addUser" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Adding..' : 'Add'}}</Button>
						</div>

					</Modal>	
				 <!-- Add modal -->	

				  <!-- Edit modal -->
				  	<Modal
						v-model="editModal"
						title="Edit Tag"
						:mask-closable="false"
						:closable="false"
						>
						<Input prefix="ios-link" clearable v-model="editData.tagName" placeholder="Tag Name" style="width: 100%" />
						<div slot="footer">
							<Button type="default" @click="closeEditModal">close</Button>
							<Button type="primary" @click="updateTag" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Updating..' : 'Update'}}</Button>
						</div>
					</Modal>
                    <!-- Edit modal -->	
				 <!-- delete modal -->
				 <deleteModal></deleteModal>
                  <!-- delete modal -->
			</div>
		</div>
	</div>
</template>

<style scoped>
.spacer{
    margin : 5px 5px;
}
.ivu-layout-sider{
    width: 200px;
    min-width: 200px;
    max-width: 200px;
    flex: 0 0 200px;
    border-radius: 4px!important;
}
.lds-ripple {
	display: inline-block;
	position: relative;
	width: 80px;
	height: 80px;
	}
	.lds-ripple div {
	position: absolute;
	border: 4px solid #bfbfbf;
	opacity: 1;
	margin: 27vh 77vh;
	border-radius: 50%;
	animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
	}
	.lds-ripple div:nth-child(2) {
	animation-delay: -0.5s;
	}
	@keyframes lds-ripple {
	0% {
		top: 36px;
		left: 36px;
		width: 0;
		height: 0;
		opacity: 1;
	}
	100% {
		top: 0px;
		left: 0px;
		width: 72px;
		height: 72px;
		opacity: 0;
	}
	}
</style>

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
				fullName:'',
				email :'',
				password :'',
				userType :'',
            },
            users:[],
			addModal: false,
			editModal: false,
			isAdding: false,
			editData: {
				fullName:'',
				email :'',
				password :'',
				userType :'',
			},
            editIndex:-1,
            
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
		const res = await this.callApi('get','/admin/users/get_users');
		
		if(res.status==200){
            this.loadingSpinner=false
			this.users = res.data
		} else {
            this.loadingSpinner=false
			this.e();
		}
	},

	methods:{

		//add Tag
		async addUser(){
			this.btnloading();
			if(this.data.fullName.trim()==''){
				this.btnloadingOff();
				return this.e('Full Name is required!');
            } 
            if(this.data.email.trim()==''){
				this.btnloadingOff();
				return this.e('E-Mail is required!');
            } 
            if(this.data.password.trim()==''){
				this.btnloadingOff();
				return this.e('Password is required!');
            } 
            if(this.data.userType.trim()==''){
				this.btnloadingOff();
				return this.e('Type is required!');
			} 
			const res = await this.callApi('post','/admin/users/create_user',this.data);
			if(res.status===201){
				this.users.unshift(res.data);
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
				this.clearEditTextField();
			} else {
				if(res.status===422){
					if(res.data.errors.tagName){
						this.i(res.data.errors.tagName[0]);
					}
				} else {
				this.btnloadingOff();
				this.e();
				}
			}
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
		clearAddModalTextField(){
			fullName =''
			email = ''
			password = ''
			userType = ''
		},
		clearEditTextField(){
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
        cancel(){
            this.clearAddModalTextField();
            this.closeAddModal();
        },
	},
}
</script>




			