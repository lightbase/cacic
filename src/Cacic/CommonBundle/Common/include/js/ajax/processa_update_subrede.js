$(document).ready(function()
	{
	$("#frmButton").click(function()
						 {
  						 var arrRedes 					= ($("#frmRedes").val()).split(",");
						 var arrItens 					= ($("#frmItens").val()).split(",");					
						 var intLoopRedes 				= 0;
						 var intLoopItens 				= 0;
		
						 for (intLoopRedes = 0; intLoopRedes < arrRedes.length; intLoopRedes++)
							{
							for (intLoopItens = 0; intLoopItens < arrItens.length; intLoopItens++)
								{						 
								var oDivProcessStatus = returnObjById("divProcessStatus_"     + arrRedes[intLoopRedes] + "_" + arrItens[intLoopItens]);							
								$(oDivProcessStatus).html('Enviando...');																			
								 $.get("processa_update_subrede.php",
								 	   {pIntIdRede:arrRedes[intLoopRedes],
									    pStrNmItem:arrItens[intLoopItens]},
									   function(pStrRetornoUpdateSubrede)
											{
											// pStrRetornoUpdateSubrede deverá conter uma string contendo informações sobre o ítem trabalhado e o status final
											// Esses valores estarão separados por _=_
											var arrRetornoUpdateSubrede = pStrRetornoUpdateSubrede.split("_=_");
											var oDivSendProcess 		= returnObjById("divSendProcess_"   + arrRetornoUpdateSubrede[0] + "_" + arrRetornoUpdateSubrede[1]);
											var oDivProcessStatus 		= returnObjById("divProcessStatus_" + arrRetornoUpdateSubrede[0] + "_" + arrRetornoUpdateSubrede[1]);
											
											if (arrRetornoUpdateSubrede[4] == 'Resended')
												{
												$(oDivSendProcess).toggleClass('div_sucesso_resended', true);
												$(oDivProcessStatus).toggleClass('div_sucesso_resended', true);
												}
											else if (arrRetornoUpdateSubrede[3] == 'Ok!')
												{
												$(oDivSendProcess).toggleClass('div_sucesso', true);
												$(oDivProcessStatus).toggleClass('div_sucesso', true);
												}
											else
												{
												$(oDivSendProcess).toggleClass('div_insucesso', true);
												$(oDivProcessStatus).toggleClass('div_insucesso', true);
												}

											$(oDivProcessStatus).html(arrRetornoUpdateSubrede[2]);											
											});
								}
							}
						});
	});
