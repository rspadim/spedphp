<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions name="NfeInutilizacao2" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2" xmlns:ns1="http://schemas.xmlsoap.org/soap/http" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <wsdl:types>
<xs:schema attributeFormDefault="unqualified" elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2" xmlns:xs="http://www.w3.org/2001/XMLSchema">
<xs:import namespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2"/>
<xs:element name="nfeCabecMsg" type="tns:nfeCabecMsg"/>
<xs:element name="nfeDadosMsg">
<xs:complexType mixed="true">
<xs:sequence>
<xs:any maxOccurs="unbounded" minOccurs="0" namespace="##other" processContents="lax"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element name="nfeInutilizacaoNF2Result" type="tns:nfeInutilizacaoNF2Result"/>
<xs:complexType mixed="true" name="nfeInutilizacaoNF2Result">
<xs:sequence>
<xs:element maxOccurs="unbounded" minOccurs="0" ref="retInutNFe"/>
</xs:sequence>
</xs:complexType>
<xs:complexType name="nfeCabecMsg">
<xs:sequence>
<xs:element minOccurs="0" name="cUF" type="xs:string"/>
<xs:element minOccurs="0" name="versaoDados" type="xs:string"/>
</xs:sequence>
<xs:anyAttribute namespace="##other" processContents="skip"/>
</xs:complexType>
</xs:schema>
<xs:schema attributeFormDefault="unqualified" elementFormDefault="unqualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2" xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2" xmlns:xs="http://www.w3.org/2001/XMLSchema">
<xs:element name="Signature" type="SignatureType"/>
<xs:element name="retInutNFe" type="TRetInutNFe"/>
<xs:complexType name="TRetInutNFe">
<xs:sequence>
<xs:element name="infInut">
<xs:complexType>
<xs:sequence>
<xs:element name="tpAmb" type="xs:string"/>
<xs:element name="verAplic" type="xs:string"/>
<xs:element name="cStat" type="xs:string"/>
<xs:element name="xMotivo" type="xs:string"/>
<xs:element name="cUF" type="xs:string"/>
<xs:element minOccurs="0" name="ano" type="xs:string"/>
<xs:element minOccurs="0" name="CNPJ" type="xs:string"/>
<xs:element minOccurs="0" name="mod" type="xs:string"/>
<xs:element minOccurs="0" name="serie" type="xs:string"/>
<xs:element minOccurs="0" name="nNFIni" type="xs:string"/>
<xs:element minOccurs="0" name="nNFFin" type="xs:string"/>
<xs:element minOccurs="0" name="dhRecbto" type="xs:anySimpleType"/>
<xs:element minOccurs="0" name="nProt" type="xs:string"/>
</xs:sequence>
<xs:attribute name="Id" type="xs:ID"/>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="Signature" type="SignatureType"/>
</xs:sequence>
<xs:attribute name="versao" type="xs:string" use="required"/>
<xs:attribute name="xmlns" type="xs:string" use="required"/>
</xs:complexType>
<xs:complexType name="SignatureType">
<xs:sequence>
<xs:element name="SignedInfo" type="SignedInfoType"/>
<xs:element name="SignatureValue" type="SignatureValueType"/>
<xs:element name="KeyInfo" type="KeyInfoType"/>
</xs:sequence>
<xs:attribute name="Id" type="xs:ID"/>
</xs:complexType>
<xs:complexType name="SignedInfoType">
<xs:sequence>
<xs:element name="CanonicalizationMethod">
<xs:complexType>
<xs:sequence/>
<xs:attribute name="Algorithm" type="xs:anyURI" use="required"/>
</xs:complexType>
</xs:element>
<xs:element name="SignatureMethod">
<xs:complexType>
<xs:sequence/>
<xs:attribute name="Algorithm" type="xs:anyURI" use="required"/>
</xs:complexType>
</xs:element>
<xs:element name="Reference" type="ReferenceType"/>
</xs:sequence>
<xs:attribute name="Id" type="xs:ID"/>
</xs:complexType>
<xs:complexType name="ReferenceType">
<xs:sequence>
<xs:element name="Transforms" type="TransformsType"/>
<xs:element name="DigestMethod">
<xs:complexType>
<xs:sequence/>
<xs:attribute name="Algorithm" type="xs:anyURI" use="required"/>
</xs:complexType>
</xs:element>
<xs:element name="DigestValue" type="xs:base64Binary"/>
</xs:sequence>
<xs:attribute name="Id" type="xs:ID"/>
<xs:attribute name="URI" type="xs:string" use="required"/>
<xs:attribute name="Type" type="xs:anyURI"/>
</xs:complexType>
<xs:complexType name="TransformsType">
<xs:sequence>
<xs:element maxOccurs="unbounded" name="Transform" type="TransformType"/>
</xs:sequence>
</xs:complexType>
<xs:complexType name="TransformType">
<xs:sequence>
<xs:element maxOccurs="unbounded" minOccurs="0" name="XPath" type="xs:string"/>
</xs:sequence>
<xs:attribute name="Algorithm" type="xs:string" use="required"/>
</xs:complexType>
<xs:complexType name="SignatureValueType">
<xs:simpleContent>
<xs:extension base="xs:base64Binary">
<xs:attribute name="Id" type="xs:ID"/>
</xs:extension>
</xs:simpleContent>
</xs:complexType>
<xs:complexType name="KeyInfoType">
<xs:sequence>
<xs:element name="X509Data" type="X509DataType"/>
</xs:sequence>
<xs:attribute name="Id" type="xs:ID"/>
</xs:complexType>
<xs:complexType name="X509DataType">
<xs:sequence>
<xs:element name="X509Certificate" type="xs:base64Binary"/>
</xs:sequence>
</xs:complexType>
<xs:complexType name="TInutNFe">
<xs:sequence>
<xs:element name="infInut">
<xs:complexType>
<xs:sequence>
<xs:element name="tpAmb" type="xs:string"/>
<xs:element name="xServ" type="xs:string"/>
<xs:element name="cUF" type="xs:string"/>
<xs:element name="ano" type="xs:string"/>
<xs:element name="CNPJ" type="xs:string"/>
<xs:element name="mod" type="xs:string"/>
<xs:element name="serie" type="xs:string"/>
<xs:element name="nNFIni" type="xs:string"/>
<xs:element name="nNFFin" type="xs:string"/>
<xs:element name="xJust" type="xs:string"/>
</xs:sequence>
<xs:attribute name="Id" type="xs:ID" use="required"/>
</xs:complexType>
</xs:element>
<xs:element name="Signature" type="SignatureType"/>
</xs:sequence>
<xs:attribute name="versao" type="xs:string" use="required"/>
</xs:complexType>
<xs:complexType name="TProcInutNFe">
<xs:sequence>
<xs:element name="inutNFe" type="TInutNFe"/>
<xs:element name="retInutNFe" type="TRetInutNFe"/>
</xs:sequence>
<xs:attribute name="versao" type="xs:string" use="required"/>
</xs:complexType>
</xs:schema>
  </wsdl:types>
  <wsdl:message name="nfeInutilizacaoNF2Response">
    <wsdl:part element="tns:nfeInutilizacaoNF2Result" name="nfeInutilizacaoNF2Result">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeInutilizacaoNF2">
    <wsdl:part element="tns:nfeDadosMsg" name="nfeDadosMsg">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:portType name="NfeInutilizacao2Soap">
    <wsdl:operation name="nfeInutilizacaoNF2" parameterOrder="nfeDadosMsg nfeCabecMsg">
      <wsdl:input message="tns:nfeInutilizacaoNF2" name="nfeInutilizacaoNF2">
    </wsdl:input>
      <wsdl:output message="tns:nfeInutilizacaoNF2Response" name="nfeInutilizacaoNF2Response">
    </wsdl:output>
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeInutilizacao2SoapBinding" type="tns:NfeInutilizacao2Soap">
    <soap12:binding style="document" transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="nfeInutilizacaoNF2">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeInutilizacao2/nfeInutilizacaoNF2" style="document"/>
      <wsdl:input name="nfeInutilizacaoNF2">
        <soap12:header message="tns:nfeInutilizacaoNF2" part="nfeCabecMsg" use="literal">
        </soap12:header>
        <soap12:body parts="nfeDadosMsg" use="literal"/>
      </wsdl:input>
      <wsdl:output name="nfeInutilizacaoNF2Response">
        <soap12:header message="tns:nfeInutilizacaoNF2Response" part="nfeCabecMsg" use="literal">
        </soap12:header>
        <soap12:body parts="nfeInutilizacaoNF2Result" use="literal"/>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeInutilizacao2">
    <wsdl:port binding="tns:NfeInutilizacao2SoapBinding" name="NfeInutilizacao2Soap12">
      <soap12:address location="https://hnfe.fazenda.mg.gov.br/nfe2/services/NfeInutilizacao2"/>
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>