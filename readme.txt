[���й�㹡����ҹ]
==============
1. copy ��ǹ #DEFINE_SYNC ���� test_connect.php ��ҧ� wp-config.php ��ѧ�ҡ�Ǣͧ $table_prefix

2. copy code ��ǹ #ACTION_SYNC_USER_BETWEEN_SITE ���� test_connect.php ��ҧ� functions.php �ͧ��������ҹ����

3. ����� setuser_api.php ��ҧ���� {site}/wp-admin/ �ͧ site �����Ѻ���

[��÷ӧҹ]
=========

{site_1 : (copy define ��ҧ� ��� 1 ��� 2)} ---> {site_2 : (�ҧ��� setuser_api.php � /wp-admin)}

- ���� site ˹���� main �����觤������ա site ˹�� ��駤�ҵ���÷�� define 㹢�� 1. ���ç�Ѻ host ��Ф�ҵ�ҧ� �����ҹ
-�ҡ��ͧ�������� 2 site sync �ѹ ���ҧ��� ��е�駤�ҵ�� [���й�㹡����ҹ] ����ͧ site