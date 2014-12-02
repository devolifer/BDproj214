delimiter //
create trigger confereValMinimo before insert on lance 
	for each row begin 
		if new.valor < (select valorbase from leilao where (leilao.nif, leilao.dia, leilao.nrleilaonodia) = 
				(select nif, dia, nrleilaonodia from leilaor where leilaor.lid = new.leilao)) 
			then call storeproc_que_nao_existe_codigo_01 ();
		
			elseif new.valor <= (select max(b.valor) from lance as b where b.leilao = new.leilao)
				then call storeproc_que_nao_existe_codigo_01 ();
		end if; 
	end;//
delimiter ; 





