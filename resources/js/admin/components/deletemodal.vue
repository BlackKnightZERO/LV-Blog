<template>
    <div>
        <!-- delete modal -->
					<Modal :value="getDeleteModalObj.showDeleteModal" 
                    :mask-closable="false"
                    :closable="true"
                    @on-cancel="cancel"
                    width="360">
						<p slot="header" style="color:#f60;text-align:center">
							<Icon type="ios-information-circle"></Icon>
							<span>Delete confirmation</span>
						</p>
						<div style="text-align:center">
							<p>Are you sure you want to delete it?</p>
						</div>
						<div slot="footer">
							<Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteObj">Delete</Button>
						</div>
					</Modal>
		<!-- delete modal -->
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    data(){
      return{
          isDeleting:false,
      }  
    },
    computed:{
        ...mapGetters(['getDeleteModalObj'])
    },
    methods:{
        async deleteObj(){
            this.isDeleting = true;
            if(this.getDeleteModalObj.deleteData.iconImage){
                this.clearOldIconImage();
            }
            const res = await this.callApi('post', this.getDeleteModalObj.deleteUrl, this.getDeleteModalObj.deleteData);
            if(res.status===200){
                this.isDeleting = false;
                this.s('Item has been deleted successfully!');
                this.$store.commit('setDeleteModal', true);
                // this.$store.commit('resetDeleteModal',-1);
                // this.$store.dispatch('reset',-1);
            } else {
                this.isDeleting = false;
                this.e();
                this.$store.commit('setDeleteModal', false);
                // this.$store.commit('resetDeleteModal',-1);
                // this.$store.dispatch('reset',-1);
            }
			
        },
        async clearOldIconImage(){
            let image = this.getDeleteModalObj.deleteData.iconImage;
            const res = await this.callApi('post','/admin/category/remove_image',{"iconImage": image});
			if(res.status===200){
				console.log('successfully deleted old pic from server');
			} else {
				console.log('operation failed: old pic deletion from server');
			}
        },
        cancel(){
            const deleteModalObj = {
									showDeleteModal:false,
									deleteUrl:'',
									deleteIndex:-1,
									isDeleted:false,
									deleteData:{

                                    },
									
			}
			this.$store.commit('setDeleteModalObj',deleteModalObj);
        },
    }
}
</script>