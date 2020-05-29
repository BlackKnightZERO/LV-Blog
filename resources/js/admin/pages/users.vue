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
								<th>Role</th>
								<th>Created At</th>
								<th>Action</th>
							</tr>
							<tr v-for="(user, index) in users" :key="index">
								<td>{{ index+1 }}</td>
								<td class="_table_name">{{ user.fullName }}</td>
                                <td>{{ user.email }}</td>
                                <td>
									<Badge status="success" v-if="user.role.isPermitted==1" />
									<Badge status="error" v-else />
									{{ user.role.roleName }}
								</td>
								<td>{{ user.created_at }}</td>
								<td>
									<Button type="info" size="small" @click="openEditModal(user, index)">Edit</Button>
									<Button type="error" size="small" @click="openDeleteModal(user, index)">Delete</Button>
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
                                        <Select prefix="md-paper-plane" v-model="data.role_id"  placeholder="User type">
                                            <!-- <Option value="Editor">Editor</Option>
                                            <Option value="Admin">Admin</Option> -->
											<Option :value="r.id" v-for="(r,i) in roles" :key="i">{{ r.roleName }}</Option>
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
						title="Edit User"
						:mask-closable="false"
						:closable="true"
						>
						<div class="spacer">
                            <Input prefix="ios-contact" type="text" clearable v-model="editData.fullName" placeholder="Full Name" style="width: 100%" />
                        </div>
						 <div class="spacer">
                             <Input prefix="ios-mail-outline" type="email" clearable v-model="editData.email" placeholder="E-Mail" style="width: 100%" />
                         </div>
						 <div class="spacer">
                             <Input prefix="ios-lock" type="password" clearable v-model="editData.password" placeholder="Password" style="width: 100%" />
                         </div>
						 <div class="spacer">
                             <Layout style="background:#ffffff!important;">
                                 <Content style="margin: auto 16px;text-align: right;"></Content>
                                    <Sider hide-trigger>
                                        <Select prefix="md-paper-plane" v-model="editData.userType"  placeholder="User type">
                                            <Option value="Editor">Editor</Option>
                                            <Option value="Admin">Admin</Option>
                                        </Select>
                                    </Sider>
                             </Layout>
                         </div>
						<div slot="footer">
							<Button type="default" @click="closeEditModal">close</Button>
							<Button type="primary" @click="updateUser" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Updating..' : 'Update'}}</Button>
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
				role_id:null,
            },
			users:[],
			roles:[],
			addModal: false,
			editModal: false,
			isAdding: false,
			editData: {
				fullName:'',
				email :'',
				password :'',
				role_id:null,
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
			// console.log(obj.deleteIndex);
			// console.log(this.users);
			if(obj.isDeleted){
				this.users.splice(obj.deleteIndex,1);
			}
		}
	},

	async created(){
		const [res,resRole] = await Promise.all([
			this.callApi('get','/admin/users/get_users'),
			this.callApi('get','/admin/role/get_roles')
		]);
		console.log(res);
		// const res = await this.callApi('get','/admin/users/get_users');
		// const resRole = await this.callApi('get','/admin/role/get_roles');
		if(res.status==200){
			this.users = res.data
		} else {
			this.e();
		}
		if(resRole.status==200){
			this.roles = resRole.data
		} else {
			this.e();
		}
		this.loadingSpinner=false
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
            if(!this.data.role_id){
				this.btnloadingOff();
				return this.e('Type is required!');
			} 
			const res = await this.callApi('post','/admin/users/create_user',this.data);
			if(res.status===200){
				this.users.unshift(res.data);
				this.btnloadingOff();
				this.s('User has been added successfully!');
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
		async updateUser(){
			this.btnloading();
			if(this.editData.fullName.trim()==''){
				this.btnloadingOff();
				return this.e('Full Name is required!');
            } 
            if(this.editData.email.trim()==''){
				this.btnloadingOff();
				return this.e('E-Mail is required!');
            } 
            if(!this.editData.role_id){
				this.btnloadingOff();
				return this.e('Type is required!');
			} 
			const res = await this.callApi('post','/admin/user/update_user',this.editData);
			if(res.status===200){
				this.users[this.editIndex].fullName = this.editData.fullName;
				this.users[this.editIndex].email = this.editData.email;
				this.users[this.editIndex].password = this.editData.password;
				this.users[this.editIndex].role_id = this.editData.role_id;

				this.btnloadingOff();
				this.s('User has been edited successfully!');
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
			role_id = null
		},
		clearEditModalTextField(){
			this.editData.id = '';
			this.editData.fullName = '';
			this.editData.email = '';
			this.editData.password = '';
			this.editData.role_id = null;
			this.editIndex = -1;
		},
		openEditModal(user, index){	
			this.editData.id = user.id;
			this.editData.fullName = user.fullName;
			this.editData.email = user.email;
			this.editData.password = user.password;
			this.editData.role_id = user.role_id;
			this.editIndex = index;
			this.editModal = true;
		},
		closeEditModal(){
			this.editData.id = '';
			this.editData.fullName = '';
			this.editData.email = '';
			this.editData.password = '';
			this.editData.role_id = null;
			this.editIndex = -1;
			this.editModal = false;
		},
		openDeleteModal(user, index){
			const deleteModalObj = {
									showDeleteModal:true,
									deleteUrl:'/admin/user/delete_user',
									deleteIndex:index,
									isDeleted:false,
									deleteData:user,
			}
			this.$store.commit('setDeleteModalObj', deleteModalObj);
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
	},
}
</script>




			